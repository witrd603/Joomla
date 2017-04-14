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

class FrontendModelGallery extends FactoryModel
{
    function getData()
    {
        $id = JFactory::getApplication()->input->getInt('user_id');
        $is_friend = $this->getIsFriend($id);
        $settings = new LovefactorySettings();

        $array = array(0);
        if ($is_friend) {
            $array[] = 1;
        }

        if ($this->getIsMyGallery()) {
            $array[] = 2;
        }

        $query = ' SELECT p.*, COUNT(c.id) AS comments, u.email AS _email'
            . ' FROM #__lovefactory_photos p'
            . ' LEFT JOIN #__lovefactory_photo_comments c ON c.photo_id = p.id AND ' . ($settings->approval_comments_photo ? ' c.approved = 1' : '1')
            . ' LEFT JOIN #__users u ON u.id = p.user_id'
            . ' WHERE p.user_id = ' . $id
            . ' AND p.status IN (' . implode(',', $array) . ')'
            . ($settings->approval_photos ? ' AND p.approved = 1' : '')
            . ' GROUP BY p.id'
            . ' ORDER BY p.status ASC, ordering ASC';
        $this->_db->setQuery($query);

        $photos = $this->_db->loadObjectList();
        $array = array(0 => array(), 1 => array(), 2 => array());

        foreach ($photos as $photo) {
            $table = $this->getTable('Photo', 'Table');
            $table->bind($photo);

            $table->comments = $photo->comments;
            $table->_email = $photo->_email;

            $array[$table->status][] = $table;
        }

        return $array;
    }

    function getIsMyGallery()
    {
        $user = JFactory::getUser();
        $id = JFactory::getApplication()->input->getInt('user_id', 0);

        return (bool)($user->id == $id);
    }

    function getProfile()
    {
        $id = JFactory::getApplication()->input->getInt('user_id', 0);

        $query = ' SELECT p.user_id, u.username'
            . ' FROM #__lovefactory_profiles p'
            . ' LEFT JOIN #__users u ON u.id = p.user_id'
            . ' WHERE p.user_id = ' . $id;
        $this->_db->setQuery($query);

        return $this->_db->loadObject();
    }

    function getPhotosForUser($user_id)
    {
        $query = ' SELECT p.*, u.email AS _email'
            . ' FROM #__lovefactory_photos p'
            . ' LEFT JOIN #__users u ON u.id = p.user_id'
            . ' WHERE p.user_id = ' . $user_id
            . ' ORDER BY p.status ASC, ordering ASC';
        $this->_db->setQuery($query);

        $photos = $this->_db->loadObjectList();
        $array = array(0 => array(), 1 => array(), 2 => array());

        foreach ($photos as $photo) {
            $table = $this->getTable('Photo', 'Table');
            $table->bind($photo);
            $table->_email = $photo->_email;

            $array[$table->status][] = $table;
        }

        return $array;
    }

    function getVideos()
    {
        $user = JFactory::getUser();

        $query = ' SELECT v.*'
            . ' FROM #__lovefactory_videos v'
            . ' WHERE v.user_id = ' . $user->id
            . ' ORDER BY v.status ASC, ordering ASC';
        $this->_db->setQuery($query);

        $videos = $this->_db->loadObjectList();
        $array = array(0 => array(), 1 => array(), 2 => array());

        foreach ($videos as $video) {
            $table = $this->getTable('LoveFactoryVideo', 'Table');
            $table->bind($video);

            $array[$table->status][] = $table;
        }

        return $array;
    }

    // Tasks
    function report()
    {
        $text = JFactory::getApplication()->input->getString('text', '');
        $gallery_id = JFactory::getApplication()->input->getInt('gallery_id', 0);

        $profile = $this->getTable('profile', 'Table');
        $profile->load($gallery_id);

        if ($profile->_is_new) {
            $this->setError(JText::_('GALLERY_TASK_REPORT_ERROR_NOT_FOUND'));
            return false;
        }

        $user = JFactory::getUser();
        $date = JFactory::getDate();
        $report = $this->getTable('report');

        $report->reporting_id = $user->id;
        $report->reported_id = $gallery_id;
        $report->user_id = $gallery_id;
        $report->type_id = 3;
        $report->comment = $text;
        $report->date = $date->toSql();

        $report->store();

        return true;
    }

    // Helpers
    function getIsFriend($id)
    {
        $user = JFactory::getUser();

        if (!$id || $user->guest) {
            return false;
        }

        if ($id == $user->id) {
            return true;
        }

        $query = ' SELECT COUNT(1)'
            . ' FROM #__lovefactory_friends f'
            . ' WHERE ((f.sender_id = ' . $user->id . ' AND f.receiver_id = ' . $id . ')'
            . ' OR (f.sender_id = ' . $id . ' AND f.receiver_id = ' . $user->id . '))'
            . ' AND f.pending = 0'
            . ' AND f.type = 1';
        $this->_db->setQuery($query);

        return (bool)$this->_db->loadResult();
    }
}
