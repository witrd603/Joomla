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

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

class com_loveFactoryInstallerScript
{
    private $oldVersion;

    public function install($parent)
    {
    }

    public function uninstall($parent)
    {
    }

    public function update($parent)
    {
    }

    public function preflight($type, $parent)
    {
        if ('update' == $type) {
            $data = $this->parseManifest();

            $this->oldVersion = $data['version'];

            list($major, $minor, $build) = explode('.', $data['version']);

            if ($major < 3 || ($major == 3 && $minor < 5)) {
                return false;
            }

            // Update #__schemas.
            $this->updateSchemasTable($data);

            jimport('joomla.filesystem.file');
            JFile::move(JPATH_ADMINISTRATOR . '/components/com_lovefactory/settings.php', JPATH_ADMINISTRATOR . '/cache/settings.php');
        }

        return true;
    }

    public function postflight($type, $parent)
    {
        $this->createMenus();

        jimport('joomla.filesystem.file');

        if ('install' == $type) {
            JFile::move(JPATH_ADMINISTRATOR . '/components/com_lovefactory/settings.txt', JPATH_ADMINISTRATOR . '/components/com_lovefactory/settings.php');
        } else {
            JFile::move(JPATH_ADMINISTRATOR . '/cache/settings.php', JPATH_ADMINISTRATOR . '/components/com_lovefactory/settings.php');

            if (version_compare($this->oldVersion, '4.3.6', '<=')) {
                $session = JFactory::getSession();
                $messages = $session->get('messages', array(), 'lovefactory');
                $messages[] = 'The tokens for the Invoice Buyer Details template have changed! Make sure you re-add the fields you want displayed using the Field button below the editor.';
                $session->set('messages', $messages, 'lovefactory');
            }

            if (version_compare($this->oldVersion, '4.3.9', '<')) {
                file_put_contents(JPATH_ADMINISTRATOR . '/components/com_lovefactory/migrations.php',
                    <<<PHP
                    <?php

JFactory::getApplication()->redirect('index.php?option=com_lovefactory&controller=migration&task=membershipsoldrestrictions');
PHP
                );
            }
        }
    }

    private function createMenus()
    {
        $menus = array(
            'main' => array(
                'menutype' => 'love-factory',
                'title' => 'Love Factory',
                'description' => 'Love Factory Menu',
                'access' => 1,

                'items' => array(
                    'quicksearch' => array(
                        'title' => 'Search',
                        'link' => 'index.php?option=com_lovefactory&view=search',
                        'access' => 1,
                    ),

                    'online' => array(
                        'title' => 'Online users',
                        'link' => 'index.php?option=com_lovefactory&view=online',
                        'access' => 1,
                    ),

                    'membersmap' => array(
                        'title' => 'Members map',
                        'link' => 'index.php?option=com_lovefactory&view=membersmap',
                        'access' => 1,
                    ),

                    'myfriends' => array(
                        'title' => 'Friends',
                        'link' => 'index.php?option=com_lovefactory&view=myfriends',
                        'access' => 2,
                    ),

                    'profile' => array(
                        'title' => 'My profile',
                        'link' => 'index.php?option=com_lovefactory&view=profile',
                        'access' => 2,
                    ),

                    'photos' => array(
                        'title' => 'My gallery',
                        'link' => 'index.php?option=com_lovefactory&view=photos',
                        'access' => 2,
                    ),

                    'inbox' => array(
                        'title' => 'Inbox',
                        'link' => 'index.php?option=com_lovefactory&view=inbox',
                        'access' => 2,
                    ),

                    'groups' => array(
                        'title' => 'Groups',
                        'link' => 'index.php?option=com_lovefactory&view=groups',
                        'access' => 2,
                    ),

                    'memberships' => array(
                        'title' => 'Memberships',
                        'link' => 'index.php?option=com_lovefactory&view=memberships',
                        'access' => 1,
                    ),

                ),
            ),
        );

        foreach ($menus as $menu) {
            $this->createMenu($menu);
        }
    }

    private function createMenu($menu)
    {
        // Check if menu exists
        if ($this->menuExists($menu['menutype'])) {
            return false;
        }

        if (!$this->createMenuType($menu['menutype'], $menu['title'], $menu['description'])) {
            return false;
        }

        if (!$this->createMenuModule($menu['title'], $menu['menutype'], $menu['access'])) {
            return false;
        }

        if (!$this->createMenuItems($menu['items'], $menu['menutype'])) {
            return false;
        }

        return true;
    }

    private function menuExists($menutype)
    {
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->select('m.id')
            ->from('#__menu_types m')
            ->where('m.menutype = ' . $dbo->quote($menutype));
        $dbo->setQuery($query);

        return $dbo->loadResult();
    }

    private function createMenuType($menutype, $title, $description)
    {
        $menu = JTable::getInstance('MenuType');

        $menu->menutype = $menutype;
        $menu->title = $title;
        $menu->description = $description;

        return $menu->store();
    }

    private function createMenuModule($title, $menutype, $access)
    {
        $module = JTable::getInstance('Module');

        $module->title = $title;
        $module->position = 'position-7';
        $module->published = 1;
        $module->module = 'mod_menu';
        $module->access = $access;
        $module->showtitle = 1;
        $module->params = '{"menutype":"' . $menutype . '","startLevel":"1","endLevel":"0","showAllChildren":"0","tag_id":"","class_sfx":"","window_open":"","layout":"_:default","moduleclass_sfx":"","cache":"1","cache_time":"900","cachemode":"itemid"}';
        $module->client_id = 0;
        $module->language = 'en-GB';

        if (!$module->store()) {
            return false;
        }

        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->insert($dbo->quoteName('#__modules_menu'))
            ->values($dbo->quote($module->id) . ', ' . $dbo->quote(0));
        $dbo->setQuery($query)
            ->execute();

//    $dbo->setQuery('INSERT INTO ' . $dbo->quoteName('#__modules_menu') . ' (moduleid, menuid) VALUES (' . $dbo->quote($module->id) . ', ' . $dbo->quote(0) . ')');
//    $dbo->query();

        return true;
    }

    private function createMenuItems($items, $menutype)
    {
        $extension = JTable::getInstance('Extension');
        $component_id = $extension->find(array('type' => 'component', 'element' => 'com_lovefactory'));

        $items = array_reverse($items, true);

        foreach ($items as $item) {
            if (!$this->createMenuItem($item, $component_id, $menutype)) {
                return false;
            }
        }

        return true;
    }

    private function createMenuItem($menuItem, $component_id, $menutype)
    {
        $item = JTable::getInstance('Menu');

        $item->menutype = $menutype;
        $item->type = 'component';
        $item->published = 1;
        $item->client_id = 0;
        $item->level = 1;
        $item->parent_id = 1;
        $item->component_id = $component_id;
        $item->title = $menuItem['title'];
        $item->alias = JFilterOutput::stringURLSafe($menuItem['title']);
        $item->link = $menuItem['link'];
        $item->access = $menuItem['access'];
        $item->language = '*';

        if (!$item->store()) {
            return false;
        }

        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->update($dbo->quoteName('#__menu'))
            ->set($dbo->quoteName('parent_id') . ' = ' . $dbo->quote(1) . ', ' . $dbo->quoteName('level') . ' = ' . $dbo->quote(1))
            ->where($dbo->quoteName('id') . ' = ' . $dbo->quote($item->id));
        $dbo->setQuery($query)
            ->execute();

//    $dbo->setQuery('UPDATE `#__menu` SET `parent_id`=1, `level`=1 WHERE `id`=' . $dbo->quote($item->id));
//    $dbo->query();

        return true;
    }

    protected function updateSchemasTable($data)
    {
        $extension = JTable::getInstance('Extension', 'JTable');
        $componentId = $extension->find(array('type' => 'component', 'element' => 'com_lovefactory'));

        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('s.version_id')
            ->from('#__schemas s')
            ->where('s.extension_id = ' . $dbo->quote($componentId));
        $result = $dbo->setQuery($query)
            ->loadResult();

        if (!$result) {
            $query = $dbo->getQuery(true)
                ->insert('#__schemas')
                ->set('extension_id = ' . $dbo->quote($componentId))
                ->set('version_id = ' . $dbo->quote($data['version']));
        } else {
            $query = $dbo->getQuery(true)
                ->update('#__schemas')
                ->set('version_id = ' . $dbo->quote($data['version']))
                ->where('extension_id = ' . $dbo->quote($componentId));
        }

        $dbo->setQuery($query)
            ->execute();
    }

    private function getManifestFilename()
    {
        $file = JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'lovefactory.xml';

        if (!file_exists($file)) {
            $file = JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'com_lovefactory.xml';
        }

        return $file;
    }

    private function parseManifest()
    {
        return JInstaller::parseXMLInstallFile($this->getManifestFilename());
    }
}
