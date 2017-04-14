<?php

/**
-------------------------------------------------------------------------
lovefactory - Love Factory 4.4.7
-------------------------------------------------------------------------
 * @author thePHPfactory
 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.thePHPfactory.com
 * Technical Support: Forum - http://www.thePHPfactory.com/forum/
-------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport('joomla.application.component.model');
jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class BackendModelBackup extends JModelLegacy
{
    protected $tempFolder;
    protected $delimiter = ',';

    public function create()
    {
        // Initialise execution settings
        $this->initialise();

        // Save the settings
        $this->createSettings();

        // Create the tables
        $this->createTables();

        // Create info file
        $this->createInfoFile();

        // Create the archive file
        $archive = $this->createArchive();

        // Send archive file to download
        $this->outputArchive($archive);

        // Delete temp folder.
        if (JFolder::exists($this->tempFolder)) {
            JFolder::delete($this->tempFolder);
        }

        jexit();
    }

    public function restore()
    {
        // Initialise execution settings
        $this->initialise();

        // Upload archive
        $archive = $this->uploadArchive();

        // Extract archive
        $this->extractArchive($archive);

        // Parse the info file
        $info = $this->getInfo();

        // Check restore version
        $this->checkRestoreVersion($info);

        // Restore the settings file
        $this->restoreSettings($info);

        // Restore the tables
        $this->restoreTables();

        // Delete temp folder.
        if (JFolder::exists($this->tempFolder)) {
            JFolder::delete($this->tempFolder);
        }

        return true;
    }

    // Create Helpers
    protected function createSettings()
    {
        // Check if we are saving the settings
        if (!$this->getSaveSettings()) {
            return false;
        }

        // Copy the settings file to the temp folder
        $src = JPATH_COMPONENT_ADMINISTRATOR . DS . 'settings.php';
        $dest = $this->tempFolder . DS . 'settings.php';
        JFile::copy($src, $dest);

        // Prepare the settings file contents
        $contents = file_get_contents($dest);
        $contents = str_replace('Lovefactory', 'LovefactoryBackup', $contents);

        // Write the settings file contents
        JFile::write($dest, $contents);

        // Check if the settings file exists
        if (!JFile::exists($dest)) {
            $this->throwError(20, JText::_('BACKUP_CREATE_SETTINGS_FILE_NOT_CREATED'));
        }

        // Copy banned words file
        $src = JPATH_COMPONENT_ADMINISTRATOR . DS . 'banned_words.php';
        $dest = $this->tempFolder . DS . 'banned_words.php';
        JFile::copy($src, $dest);

        // Check if the banned words file exists
        if (!JFile::exists($dest)) {
            $this->throwError(30, JText::_('BACKUP_CREATE_BANNED_WORDS_FILE_NOT_CREATED'));
        }

        $settings = JComponentHelper::getParams('com_lovefactory');
        $contents = $settings->toString();
        JFile::write($this->tempFolder . '/settings_db.txt', $contents);

        return true;
    }

    protected function createTables()
    {
        $tables = $this->getTables();

        foreach ($tables as $table) {
            $this->createTable($table);
        }
    }

    protected function createTable($table)
    {
        $dbo = $this->getDbo();
        $tableName = $dbo->replacePrefix($table);
        $fileName = $this->tempFolder . '/' . $table . '.sql';
        $statements = array();

        $createStm = $dbo->getTableCreate($table);

        $statements[] = 'DROP TABLE IF EXISTS ' . $dbo->qn($table) . ';';
        $statements[] = str_replace($tableName, $table, $createStm[$table]) . ';';

        $query = $dbo->getQuery(true)
            ->select('t.*')
            ->from($dbo->quoteName($tableName) . ' t');
        $results = $dbo->setQuery($query)
            ->loadAssocList();

        foreach ($results as $result) {
            $statements[] = 'INSERT INTO ' . $dbo->qn($table) . ' VALUES (' . implode(',', $dbo->q($result)) . ');';
        }

        file_put_contents($fileName, implode("\r\n", $statements));

        return true;
    }

    protected function createInfoFile()
    {
        $date = JFactory::getDate();
        $dest = $this->tempFolder . DS . 'info.json';

        // Prepare the output
        $output = array(
            'love_factory_version' => $this->getLovefactoryVersion(),
            'timestamp' => time(),
            'save_settings' => $this->getSaveSettings(),
            'date' => $date->toSql(),
        );

        // Write the info file
        JFile::write($dest, json_encode($output));

        // Check if info file exists
        if (!JFile::exists($dest)) {
            $this->throwError(50, JText::_('BACKUP_CREATE_INFO_FILE_NOT_CREATED'));
        }

        return true;
    }

    protected function createArchive()
    {
        $name = 'Love_Factory_Backup_' . date('Ymd_His') . '.zip';
        $dest = $this->tempFolder . DS . $name;
        $filesToAdd = array();

        // Get the files for the archive
        $files = JFolder::files($this->tempFolder);

        // Check if there are any files to archive
        if (!count($files)) {
            $this->throwError(60, JText::_('BACKUP_CREATE_NO_FILES_FOR_ARCHIVE'));
        }

        // Parse the files
        foreach ($files as $file) {
            $data = file_get_contents($this->tempFolder . DS . $file);
            $filesToAdd[] = array('name' => $file, 'data' => $data);
        }

        // Create the archive
        $zip = JArchive::getAdapter('zip');
        $zip->create($dest, $filesToAdd);

        // Check if archive file exists
        if (!JFile::exists($dest)) {
            $this->throwError(70, JText::_('BACKUP_CREATE_ARCHIVE_FILE_NOT_CREATED'));
        }

        // Check if archive file is readable
        if (!is_readable($dest)) {
            $this->throwError(80, JText::_('BACKUP_CREATE_ARCHIVE_FILE_NOT_READABLE'));
        }

        // Check if archive file is an archive
        if (!$zip->checkZipData(file_get_contents($dest))) {
            $this->throwError(90, JText::_('BACKUP_CREATE_ARCHIVE_FILE_IS_NOT_AN_ARCHIVE'));
        }

        return $name;
    }

    protected function outputArchive($archive)
    {
        $src = $this->tempFolder . DS . $archive;
        $filesize = filesize($src);

        // Send the archive
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Content-Type: application/zip");
        header('Content-Disposition: attachment; filename="' . $archive . '"');
        header("Content-Length: " . $filesize);
        header("Content-size: " . $filesize);
        header('Content-Transfer-Encoding: binary');

        @ob_end_clean();
        readfile($src);
        @ob_start(false);

        return true;
    }

    // Restore Helpers
    protected function uploadArchive()
    {
        $file = JFactory::getApplication()->input->files->get('backup_file', array(), 'raw');

        // Check if file uploaded
        if ($file['error'] == 4) {
            $this->throwError(100, JText::_('BACKUP_RESTORE_ARCHIVE_FILE_NOT_UPLOADED'));
        }

        // Check for errors
        if ($file['error'] || $file['size'] < 1) {
            $this->throwError(110, JText::sprintf('BACKUP_RESTORE_ARCHIVE_FILE_ERROR', $file['error']));
        }

        return $file;
    }

    protected function extractArchive($archive)
    {
        // Extract archive contents
        $dest = $this->tempFolder;

        $zip = JArchive::getAdapter('zip');
        $zip->extract($archive['tmp_name'], $dest);

        // Check if any files have been extracted
        $files = JFolder::files($dest);
        if (!$files) {
            $this->throwError(120, JText::_('BACKUP_RESTORE_NO_FILES_EXTRACTED'));
        }
    }

    protected function getInfo()
    {
        $path = $this->tempFolder . DS . 'info.json';
        $info = array();

        if (JFile::exists($path)) {
            $contents = file_get_contents($path);
            $info = json_decode($contents);
        }

        return (object)$info;
    }

    protected function checkRestoreVersion($info)
    {
        $version = intval(str_replace('.', '', $info->love_factory_version));
        $current = intval(str_replace('.', '', $this->getLovefactoryVersion()));

        if ($version != $current) {
            $this->throwError(130, JText::_('BACKUP_RESTORE_VERSION_TOO_OLD'));
        }
    }

    protected function restoreSettings($info)
    {
        // Check if settings are saved
        if (!$info->save_settings) {
            return false;
        }

        // Check if settings file exists
        $src = $this->tempFolder . DS . 'settings.php';
        if (!JFile::exists($src)) {
            $this->throwError(140, JText::_('BACKUP_RESTORE_SETTINGS_FILE_NOT_FOUND'));
        }

        require_once($src);

        $backupSettings = new LovefactoryBackupSettings();
        $model = JModelLegacy::getInstance('settings', 'BackendModel');
        $model->restoreBackup($backupSettings);

        // Copy banned words file
        $src = $this->tempFolder . DS . 'banned_words.php';
        $dest = JPATH_COMPONENT_ADMINISTRATOR . DS . 'banned_words.php';

        if (JFile::exists($src)) {
            JFile::copy($src, $dest);
        }

        // Restore db settings.
        $file = $this->tempFolder . '/settings_db.txt';
        if (file_exists($file)) {
            $contents = file_get_contents($file);

            $extension = JTable::getInstance('Extension');
            $extension->load(array('element' => 'com_lovefactory', 'type' => 'component'));

            if (isset($extension->extension_id) && $extension->extension_id) {
                $extension->params = $contents;
                $extension->store();
            }
        }
    }

    protected function restoreTables()
    {
        $tables = $this->getTables();
        $files = JFolder::files($this->tempFolder, '#__');

        foreach ($files as $i => $file) {
            $files[$i] = str_replace('.sql', '', $file);
        }

        $tables = array_intersect($tables, $files);

        foreach ($tables as $table) {
            $this->restoreTable($table);
        }
    }

    protected function restoreTable($table)
    {
        $dbo = $this->getDbo();
        $tableName = $dbo->replacePrefix($table);
        $fileName = $this->tempFolder . DS . $table . '.sql';

        $contents = JDatabaseDriver::splitSql(file_get_contents($fileName));

        foreach ($contents as $content) {
            $content = str_replace($table, $tableName, $content);

            $dbo->setQuery($content)
                ->execute();
        }

        return true;
    }

    // Helpers
    protected function getTables()
    {
        static $tables = null;

        if (is_null($tables)) {
            $path = JPATH_COMPONENT_ADMINISTRATOR . DS . 'sqls' . DS . 'uninstall.mysql.utf8.sql';
            $contents = file_get_contents($path);

            preg_match_all('/\`(#__lovefactory_[a-z_]{1,})\`/', $contents, $matches);

            $tables = $matches[1];

            $tables[] = '#__users';
            $tables[] = '#__user_usergroup_map';
            $tables[] = '#__usergroups';
        }

        return $tables;
    }

    protected function throwError($code, $error)
    {
        $app = JFactory::getApplication();
        $app->enqueueMessage(JText::_($error), 'error');
        $app->redirect('index.php?option=com_lovefactory&view=settings&layout=backup');
    }

    protected function getSaveSettings()
    {
        return JFactory::getApplication()->input->getInt('save_settings');
    }

    protected function getLovefactoryVersion()
    {
        $file = JPATH_COMPONENT_ADMINISTRATOR . DS . 'lovefactory.xml';
        $data = JInstaller::parseXMLInstallFile($file);

        return $data['version'];
    }

    protected function initialise()
    {
        ini_set('max_execution_time', '120');
        ini_set('memory_limit', '128M');

        // Set the temporary folder path
        $this->tempFolder = JPATH_COMPONENT_ADMINISTRATOR . DS . 'temp';

        // Create the temporary folder
        $this->createTempFolder();
    }

    protected function createTempFolder()
    {
        if (JFolder::exists($this->tempFolder)) {
            JFolder::delete($this->tempFolder);
        }

        JFolder::create($this->tempFolder);

        if (!JFolder::exists($this->tempFolder)) {
            $this->throwError(10, JText::_('BACKUP_CREATE_TEMP_FOLDER_NOT_CREATED'));
        }
    }
}
