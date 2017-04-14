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

class TablePhoto extends JTable
{
    public function __construct(&$db = null)
    {
        if (is_null($db)) {
            $db = JFactory::getDbo();
        }

        parent::__construct('#__lovefactory_photos', 'id', $db);
    }

    public function check()
    {
        if (!parent::check()) {
            return false;
        }

        // Set the uploaded date.
        if (is_null($this->date_added)) {
            $this->date_added = JFactory::getDate()->toSql();
        }

        // Set the ordering.
        if (is_null($this->ordering)) {
            $this->ordering = self::getNextOrder($this->getDbo()->quoteName('user_id') . '=' . $this->getDbo()->quote($this->user_id));
        }

        return true;
    }

    public function delete($pk = null)
    {
        $k = $this->_tbl_key;
        $pk = (is_null($pk)) ? $this->$k : $pk;

        jimport('joomla.filesystem.file');
        $app = LoveFactoryApplication::getInstance();

        // Delete files
        $filename = $app->getUserFolder($this->user_id) . DS . $this->filename;
        $thumb = $app->getUserFolder($this->user_id) . DS . 'thumb_' . $this->filename;

        if ($this->user_id && $this->filename && JFile::exists($filename)) {
            JFile::delete($filename);
        }

        if ($this->user_id && $this->filename && JFile::exists($thumb)) {
            JFile::delete($thumb);
        }

        if (!parent::delete($pk)) {
            return false;
        }

        // Delete item comments.
        $table = JTable::getInstance('ItemComment', 'Table');
        $table->deleteForItem($pk, 'photo');

        JEventDispatcher::getInstance()->trigger('onLoveFactoryPhotoRemoved', array(
            'com_lovefactory.photo_removed',
            $this
        ));

        return true;
    }

    /**
     * Returns the url for the photo.
     * @param bool $thumbnail
     * @return bool|string
     */
    public function getSource($thumbnail = false)
    {
        jimport('joomla.filesystem.file');

        $app = LoveFactoryApplication::getInstance();
        $user = JFactory::getUser();
        $settings = $app->getSettings();
        $admin = JFactory::getApplication()->isAdmin();
        $default = $app->getAssetsFolder('images', true) . 'love.png';

        // 1. Check if photo is approved or it's the user's photo.
        if (!$admin && $settings->approval_photos && !$this->approved && $this->user_id != $user->id) {
            return $default;
        }

        $prefix = $thumbnail ? 'thumb_' : '';
        $filename = $prefix . $this->filename;
        $src = $app->getUserFolder($this->user_id, false) . DS . $filename;

        // 2. Check if photo exists.
        if (JFile::exists($src)) {
            return $app->getUserFolder($this->user_id, true) . $filename;
        }

        // 3. Check if photo is a Gravatar.
        if ('gravatar' == $this->filename && $settings->enable_gravatar_integration) {
            $email = md5(strtolower(trim(JFactory::getUser($this->user_id)->email)));
            $size = $thumbnail ? max(array($settings->thumbnail_max_width, $settings->thumbnail_max_height)) : 512;

            return 'http://www.gravatar.com/avatar/' . $email . '?s=' . $size . '.jpg';
        }

        return $default;
    }

    function getThumbnailSource()
    {
        $app = LoveFactoryApplication::getInstance();

        return $app->getUserFolder($this->user_id, true) . $this->filename;
    }

    function setAsMain()
    {
        $profile = JTable::getInstance('profile', 'Table');
        $profile->load($this->user_id);

        $profile->main_photo = $this->filename;
        $profile->store();

        $this->is_main = 1;

        return parent::store();
    }

    public function approve()
    {
        $this->approved = 1;

        if (!$this->store()) {
            return false;
        }

        JEventDispatcher::getInstance()->trigger('onLoveFactoryPhotoApproved', array(
            'com_lovefactory.photo_approved',
            $this
        ));

        return true;
    }

    public function reject()
    {
        if (!$this->delete()) {
            return false;
        }

        JEventDispatcher::getInstance()->trigger('onLoveFactoryPhotoRejected', array(
            'com_lovefactory.photo_rejected',
            $this
        ));

        return true;
    }

    public function report()
    {
        $this->reported = 1;

        return $this->store();
    }

    /**
     * Returns the total number of photos the requested user has.
     *
     * @param null $userId
     * @return mixed
     */
    public function getCountForUser($userId = null, $statuses = array())
    {
        if (is_null($userId)) {
            $userId = JFactory::getUser();
        }

        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('COUNT(p.id)')
            ->from($dbo->quoteName($this->getTableName()) . ' p')
            ->where('p.user_id = ' . $dbo->quote($userId));

        if ($statuses) {
            $query->where('p.status IN (' . $dbo->q(implode(',', $statuses)) . ')');
        }

        $result = $dbo->setQuery($query)
            ->loadResult();

        return $result;
    }
}
