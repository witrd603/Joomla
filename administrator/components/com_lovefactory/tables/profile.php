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

JLoader::register('LoveFactoryTable', JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/methods.php');

class TableProfile extends LoveFactoryTable
{
    public $user_id;
    public $trials;
    public $filled;
    public $_is_new = true;
    public $membership_sold_id;
    protected $_settings = null;
    protected $source;

    public function __construct(&$db = null)
    {
        if (is_null($db)) {
            $db = JFactory::getDbo();
        }

        parent::__construct('#__lovefactory_profiles', 'user_id', $db);
    }

    public function load($keys = null, $reset = true)
    {
        if (!parent::load($keys)) {
            return false;
        }

        $this->_is_new = count($this->_errors) ? true : false;

        if (is_null($this->user_id)) {
            $this->_is_new = true;
        }

        return true;
    }

    public function loadUsername($username)
    {
        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('u.id')
            ->from('#__users u')
            ->where('u.username = ' . $dbo->quote($username));
        $result = $dbo->setQuery($query)
            ->loadResult();

        return $this->load($result);
    }

    public function increaseTrials()
    {
        $this->trials = 1;

        return $this->store();
    }

    /**
     * @return TableMembershipSold
     */
    public function getMembership()
    {
        $membership = JTable::getInstance('MembershipSold', 'Table');
        $membership->load($this->membership_sold_id);

        return $membership;
    }

    public function store($updateNulls = false)
    {
        // Check if user is registering.
        $table = JTable::getInstance('Profile', 'Table');
        if (!$table->load($this->user_id)) {
            return $this->getDbo()->insertObject($this->getTableName(), $this, 'user_id');
        }

        return parent::store($updateNulls);
    }

    public function bindFromFields($fields)
    {
        foreach ($fields as $field) {
            if (property_exists($this, $field->getProfileId())) {
                $this->{$field->getProfileId()} = $field->getValueToProfile();
            }

            if (property_exists($this, $field->getId() . '_visibility')) {
                $this->{$field->getId() . '_visibility'} = $field->getVisibility();
            }
        }
    }

    public function bindFromRequest($request)
    {
        foreach ($request as $field => $value) {
            // We don't to remove current photo when updating profile with photo profile field.
            if ('main_photo' === $field && null === $value) {
                continue;
            }

            if (property_exists($this, $field)) {
                $this->$field = $value;
            }
        }
    }

    public function createUserFolder()
    {
        jimport('joomla.filesystem.folder');
        $app = LoveFactoryApplication::getInstance();

        if (!JFolder::exists($app->getUserFolder($this->user_id))) {
            JFolder::create($app->getUserFolder($this->user_id));
        }

        return true;
    }

    public function addDefaultInTable()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $params = JComponentHelper::getParams('com_lovefactory');

        $table = JTable::getInstance('Profile', 'Table');
        $result = $table->load($this->user_id);

        $this->date = JFactory::getDate()->toSql();
        $this->membership_sold_id = 0;

        if (!$result) {
            $dbo = $this->getDbo();

            $this->online = $params->get('profile_settings.online', 0);
            $this->banned = 0;
            $this->loggedin = 0;
            $this->lastvisit = 0;
            $this->rating = 0;
            $this->votes = 0;
            $this->main_photo = 0;
            $this->relationship = 0;
            $this->trials = 0;
            $this->status = '';

            $dbo->insertObject($this->getTableName(), $this, 'user_id');
        }

        return true;
    }

    public function isMyProfile()
    {
        return JFactory::getUser()->id == $this->user_id;
    }

    public function delete($pk = null)
    {
        if (!parent::delete($pk)) {
            return false;
        }

        JEventDispatcher::getInstance()->trigger('onUserRemoved', array(
            'com_lovefactory',
            $this,
        ));

        return true;
    }

    /**
     * Sets profile photo.
     *
     * @param $photoId
     * @return bool
     */
    public function setProfilePhoto($photoId)
    {
        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->update('#__lovefactory_profiles')
            ->set('main_photo = ' . $dbo->quote($photoId))
            ->where('user_id = ' . $dbo->quote($this->user_id));
        $dbo->setQuery($query);

        if (!$dbo->execute()) {
            return false;
        }

        return true;
    }

    /**
     * Returns the url of the profile photo.
     * @param bool $thumbnail
     * @return string
     */
    public function getProfilePhotoSource($thumbnail = false)
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        // 1. Check if main photo is set and is valid.
        if ($this->main_photo) {
            /* @var $photo TablePhoto */
            $photo = JTable::getInstance('Photo', 'Table');
            $photo->load($this->main_photo);

            $src = $photo->getSource($thumbnail);

            $user = JFactory::getUser();
            $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('avatar_access');
            $pixelated = false;

            try {
                $restriction->isAllowed($user->id, $this->user_id);
            } catch (Exception $e) {
                $pixelated = true;
            }

            if (false !== $src && !$settings->approval_photos || $photo->approved) {
                if (!$pixelated) {
                    return $src;
                }

                $filename = \ThePhpFactory\LoveFactory\Helper\PhotoManipulation::cacheAndPixelate($this->user_id, $src);

                return JUri::root() . '/cache/com_lovefactory/pixelated/' . $filename;
            }
        }

        $app = LoveFactoryApplication::getInstance();
        $settings = $app->getSettings();

        // 2. Check if default photo is set depending on gender and gender photo exists.
        if ($settings->default_photo_extension) {
            $extension = str_replace('.', '', $settings->default_photo_extension);
            $src = $app->getStorageFolder() . DS . 'defaults' . DS . $this->sex . '.' . $extension;

            if (JFile::exists($src)) {
                return $app->getStorageFolder(true) . 'defaults/' . $this->sex . '.' . $extension;
            }
        }

        // 3. Return the default generic profile photo.
        return $app->getAssetsFolder('images', true) . 'love.png';;
    }

    /**
     * Proxy function to getProfilePhotoSource.
     * @param bool $thumbnail
     * @return string
     */
    public function getMainPhotoSource($thumbnail = false)
    {
        return $this->getProfilePhotoSource($thumbnail);
    }

    public function getParameter($name)
    {
        $settings = $this->getSettings();
        $updatable = $settings->get('profile_settings.enable.params.' . $name, 1);
        $default = $settings->get('profile_settings.params.' . $name);

        $registry = new \Joomla\Registry\Registry($this->params);

        if (!$updatable) {
            return $default;
        }

        if ('infobar' === $name) {
            JLoader::register('LoveFactoryApplication', JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/application.php');
            $settingsValue = JComponentHelper::getParams('com_lovefactory')->get('infobar.location', 1);
            $profileValue = $registry->get($name, $default);

            if (!$profileValue) {
                return 0;
            }

            if (1 != $settingsValue) {
                return $settingsValue;
            }
        }

        return $registry->get($name, $default);
    }

    public function getSettings()
    {
        return $this->_settings;
    }

    public function setSettings($settings = null)
    {
        $this->_settings = $settings;

        return $this;
    }

    public function bind($src, $ignore = array())
    {
        if (!parent::bind($src, $ignore)) {
            return false;
        }

        $this->source = $src;

        return true;
    }

    public function getSource($name = null, $default = null)
    {
        if (null === $name) {
            return $this->source;
        }

        return isset($this->source->$name) ? $this->source->$name : $default;
    }

    public function hasDefaultMembership()
    {
        if (0 == $this->membership_sold_id) {
            return true;
        }

        $membershipSold = JTable::getInstance('MembershipSold', 'Table');
        $membership = JTable::getInstance('Membership', 'Table');

        $membershipSold->load($this->membership_sold_id);
        $membership->load($membershipSold->membership_id);

        if ($membership->default) {
            return true;
        }

        return false;
    }

    protected function markOldMembershipAsExpired()
    {
        /** @var TableMembershipSold $membership */
        $membership = JTable::getInstance('MembershipSold', 'Table');
        $membership->markAsExpired($this->user_id);
    }

    protected function createSoldMembership(TableMembership $membership, JDate $expiration = null)
    {
        /** @var TableMembershipSold $soldMembership */
        $soldMembership = JTable::getInstance('MembershipSold', 'Table');
        $soldMembership->createFrom($this->user_id, $membership, $expiration);
        $soldMembership->store();

        return $soldMembership;
    }

    public function isFilled()
    {
        return $this->filled;
    }

    public function __debugInfo()
    {
        return $this->getProperties();
    }
}
