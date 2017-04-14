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

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

class LoveFactoryApplication
{
    protected $path_administrator;
    protected $path_site;
    /* @var LoveFactorySettings */
    protected $settings = null;
    protected $option = 'com_lovefactory';

    public function __construct()
    {
        $this->path_administrator = JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory';
        $this->path_site = JPATH_SITE . DS . 'components' . DS . 'com_lovefactory';

        require_once $this->path_administrator . DS . 'settings.php';
        $this->setSettings(new LovefactorySettings());

        JLoader::register('LoveFactoryPage', $this->path_site . DS . 'lib' . DS . 'vendor' . DS . 'page.php');
        JLoader::register('LoveFactoryPageRenderer', $this->path_site . DS . 'lib' . DS . 'vendor' . DS . 'renderer' . DS . 'renderer.php');
        JLoader::register('LoveFactoryGoogleMaps', $this->path_site . DS . 'lib' . DS . 'vendor' . DS . 'googlemaps.php');

        JLoader::register('LoveFactoryField', $this->path_administrator . DS . 'lib' . DS . 'fields' . DS . 'field.php');
        JLoader::register('LoveFactoryFieldSingleChoiceInterface', $this->path_site . DS . 'lib' . DS . 'vendor' . DS . 'page.php');

        JLoader::discover('Table', $this->path_administrator . DS . 'tables');
    }

    /**
     * @param string $name
     * @param null $default
     * @return LoveFactorySettings
     */
    public function getSettings($name = '', $default = null)
    {
        if (empty($name)) {
            return $this->settings;
        }

        if (isset($this->settings->$name)) {
            return $this->settings->$name;
        }

        return $default;
    }

    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    public static function getInstance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    public function getUserFolder($user_id, $url = false, $mode = null)
    {
        if (is_null($mode)) {
            $mode = $this->getSettings('photos_storage_mode');
        }

        if (1 == $mode) {
            if (!$url) {
                $folder = $this->getPhotosFolder($url) . DS . $user_id;

                jimport('joomla.filesystem.folder');

                if (!JFolder::exists($folder)) {
                    JFolder::create($folder);
                }

                return $folder;
            }

            return $this->getPhotosFolder($url) . $user_id . '/';
        }

        return $this->getPhotosFolder($url);
    }

    public function getStorageFolder($url = false)
    {
        if (!$url) {
            return JPATH_SITE . '/media/com_lovefactory/storage';
        }

        return $this->getRoot() . 'media/com_lovefactory/storage/';
    }

    public function getPhotosFolder($url = false)
    {
        if (!$url) {
            return $this->getStorageFolder($url) . DS . 'photos';
        }

        return $this->getStorageFolder($url) . 'photos/';
    }

    public function getAssetsFolder($type = '', $url = false)
    {
        if (!$url) {
            return JPATH_SITE . DS . 'components' . DS . 'com_lovefactory' . DS . 'assets' . (!empty($type) ? DS . $type : '');
        }

        return $this->getRoot() . 'components/com_lovefactory/assets/' . (!empty($type) ? $type . '/' : '');
    }

    public function getRegistrationFolder($url = false)
    {
        if (!$url) {
            return $this->getStorageFolder($url) . DS . 'registration_photos';
        }

        return $this->getStorageFolder($url) . 'registration_photos/';
    }

    public function getOption()
    {
        return $this->option;
    }

    public function getComponent()
    {
        return 'LoveFactory';
    }

    public function getPath($src)
    {
        $path = '';

        switch ($src) {
            case 'libraries':
                $path = JPATH_ADMINISTRATOR . DS . 'components' . DS . $this->option . DS . 'libraries';
                break;

            case 'factory':
                $path = $this->getPath('libraries') . DS . 'factory';
                break;

            case 'component':
                $path = JPATH_BASE . DS . 'components' . DS . $this->option;
                break;

            case 'component_site':
                $path = JPATH_SITE . DS . 'components' . DS . $this->option;
                break;

            case 'component_administrator':
                $path = JPATH_ADMINISTRATOR . DS . 'components' . DS . $this->option;
                break;

            case 'players':
                $path = $this->getPath('component_site') . DS . 'players';
                break;

            case 'storage':
                $path = $this->getPath('component_site') . DS . 'storage';
                break;

            case 'storage_secure':
                $path = $this->getPath('component_site') . DS . 'storage_secure';
                break;

            case 'views':
                $path = $this->getPath('component') . DS . 'views';
                break;

            case 'payment_gateways':
                $path = $this->getPath('payment') . DS . 'gateways';
                break;

            case 'payment':
                $path = $this->getPath('component_administrator') . DS . 'payment';
                break;

            case 'categories_thumbnails':
                $path = $this->getPath('storage') . DS . 'thumbnails';
                break;
        }

        return $path;
    }

    /**
     * @return Smarty
     */
    public function getSmarty($viewName = 'default')
    {
        static $instances = array();

        if (!isset($instances[$viewName])) {
            JLoader::register('Smarty', $this->getPath('component_site') . DS . 'lib' . DS . 'Smarty' . DS . 'Smarty.class.php');
            $smarty = new Smarty();

            $smarty->setCompileDir(JPATH_ROOT . DS . 'cache' . DS . 'com_lovefactory' . DS . 'templates');
            $smarty->setCacheDir(JPATH_ROOT . DS . 'cache' . DS . 'com_lovefactory' . DS . 'templates');
            $smarty->setPluginsDir(JPATH_SITE . '/components/com_lovefactory/lib/Smarty/plugins');

            $instances[$viewName] = $smarty;
        }

        return $instances[$viewName];
    }

    public function filterBannedWords($text)
    {
        static $bannedWords = null;

        if (!$this->settings->enable_banned_words_filter) {
            return $text;
        }

        if (is_null($bannedWords)) {
            $bannedWords = array();
            $path = $this->path_administrator . DS . 'banned_words.php';

            if (JFile::exists($path)) {
                require_once $path;

                foreach ($banned_words as $banned) {
                    $bannedWords[] = '/\b' . preg_quote($banned, '/') . '\b/i';
                }
            }
        }

        if ($bannedWords) {
            $text = preg_replace($bannedWords, '***', $text);
        }

        return $text;
    }

    protected function getRoot()
    {
        $root = str_replace('components/com_chatfactory/', '', JUri::root());

        return $root;
    }
}
