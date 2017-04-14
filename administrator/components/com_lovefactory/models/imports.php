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

class BackendModelImports extends JModelLegacy
{
    public function getItems()
    {
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');

        JLoader::register('LoveFactoryImport', JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/imports/import.php');

        $files = JFolder::files(JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/imports/adaptors', '.php');
        $items = array();

        foreach ($files as $file) {
            $name = JFile::stripExt($file);
            $adaptor = LoveFactoryImport::getInstance($name);

            if (!$adaptor) {
                continue;
            }

            $extension = JTable::getInstance('Extension', 'JTable');
            if (!$extension->load(array('type' => 'component', 'element' => (string)$adaptor->getXml()->attributes()->extension))) {
                continue;
            }

            $items[] = $adaptor;
        }

        return $items;
    }
}
