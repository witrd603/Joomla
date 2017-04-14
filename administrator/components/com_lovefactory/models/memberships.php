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

class BackendModelMemberships extends FactoryModel
{
    var $_data;
    var $_total;
    var $_pagination;

    function __construct()
    {
        parent::__construct();

        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->get('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest($option . '.memberships.limitstart', 'limitstart', 0, 'int');

        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }

    // List
    function getData()
    {
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));

            $membership = JTable::getInstance('Membership', 'Table');
            $membership->loadDefault();

            $model = JModelLegacy::getInstance('Dashboard', 'BackendModel');
            $freeMemberships = $model->countFreeMemberships();

            foreach ($this->_data as $result) {
                if ($result->id != $membership->id) {
                    continue;
                }

                $result->users += $freeMemberships;
            }
        }

        return $this->_data;
    }

    function _buildQuery()
    {
        $orderby = $this->_buildContentOrderBy();
        $where = $this->_buildContentWhere();

        $query = ' SELECT m.*, COUNT(p.user_id) AS users'
            . ' FROM #__lovefactory_memberships m'
            . ' LEFT JOIN #__lovefactory_memberships_sold s ON s.membership_id = m.id'
            . ' LEFT JOIN #__lovefactory_profiles p ON p.membership_sold_id = s.id'
            . $where
            . ' GROUP BY m.id'
            . $orderby;

        return $query;
    }

    function _buildContentOrderBy()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');

        $filter_order = $mainframe->getUserStateFromRequest($option . '.memberships.filter_order', 'filter_order', 'title', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option . '.memberships.filter_order_Dir', 'filter_order_Dir', 'asc', 'word');

        $orderby = '';

        if (in_array($filter_order, array('m.title', 'm.max_friends',
            'm.max_photos', 'm.max_messages_per_day',
            'm.max_interactions_per_day',
            'm.same_gender_interaction',
            'm.ordering', 'm.published',
            'users', 'm.gmaps',
            'm.shoutbox', 'm.chatfactory',
            'm.id', 'm.max_videos'))) {
            switch ($filter_order) {
                case 'users':
                    $filter_order = 'COUNT(p.user_id)';
                    break;
            }

            $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;
        }

        return $orderby;
    }

    function _buildContentWhere()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');

        $state = $mainframe->getUserStateFromRequest($option . '.memberships.filter_state', 'filter_state', '', 'cmd');

        $where = array();

        if ($state != '') {
            $state = ($state == 'P') ? 1 : 0;
            $where[] = ' m.published = ' . $state;
        }

        return $where ? ' WHERE ' . implode(' AND ', $where) : false;
    }

    function getTotal()
    {
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }

        return $this->_total;
    }

    function getPagination()
    {
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_pagination;
    }

    function checkDefaultSoldMembership()
    {
        $membership = $this->getTable('membershipsold', 'Table');
        $membership->load(1);

        /* @var $membership MembershipSoldTable */
        if (count($membership->_errors)) {
            $query = ' SELECT m.*'
                . ' FROM #__lovefactory_memberships m'
                . ' WHERE m.default = 1';
            $this->_db->setQuery($query);
            $default = $this->_db->loadObject();

            $membership->title = $default->title;
            $membership->membership_id = $default->id;
            $membership->default = 1;
            $membership->max_photos = $default->max_photos;
            $membership->max_videos = $default->max_videos;
            $membership->max_friends = $default->max_friends;
            $membership->max_messages_per_day = $default->max_messages_per_day;
            $membership->max_interactions_per_day = $default->max_interactions_per_day;
            $membership->shoutbox = $default->shoutbox;
            $membership->chatfactory = $default->chatfactory;
            $membership->top_friends = $default->top_friends;

            $this->_db->insertObject($membership->getTableName(), $membership, 1);
        }
    }

    function getShoutbox()
    {
        $membership = $this->getTable('membership');

        return $membership->getShoutboxValues();
    }

    function getAccess()
    {
        $array = array(
            'advancedsearch' => 'MEMBERSHIPS_ACCESS_ADVANCED_SEARCH',
            'blacklist' => 'MEMBERSHIPS_ACCESS_IGNORE_LIST',
            'comment' => 'MEMBERSHIPS_ACCESS_COMMENT',
            'friend' => 'MEMBERSHIPS_ACCESS_FRIENDSHIP',
            'friends' => 'MEMBERSHIPS_ACCESS_FRIENDS_LIST',
//      'friendspending' => 'MEMBERSHIPS_ACCESS_PENDING_REQUESTS',
//      'gallery'        => 'MEMBERSHIPS_ACCESS_GALLERY',
            'photos' => 'MEMBERSHIPS_ACCESS_PHOTOS',
            'videos' => 'MEMBERSHIPS_ACCESS_VIDEOS',
            'inbox' => 'MEMBERSHIPS_ACCESS_INBOX',
            'interaction' => 'MEMBERSHIPS_ACCESS_INTERACTION',
            'interactions' => 'MEMBERSHIPS_ACCESS_INTERACTIONS_LIST',
//      'mailbox'        => 'MEMBERSHIPS_ACCESS_MAILBOX',
            'membersmap' => 'MEMBERSHIPS_ACCESS_MEMBERS_MAP',
//      'message'        => 'MEMBERSHIPS_ACCESS_MESSAGE',
            'messageread' => 'MEMBERSHIPS_ACCESS_READ_MESSAGE',
            'messagewrite' => 'MEMBERSHIPS_ACCESS_WRITE_MESSAGE',
//      'myfriends'      => 'MEMBERSHIPS_ACCESS_MY_FRIENDS_LIST',
//      'mygallery'      => 'MEMBERSHIPS_ACCESS_MY_GALLERY',
            'online' => 'MEMBERSHIPS_ACCESS_ONLINE_USERS',
            'outbox' => 'MEMBERSHIPS_ACCESS_OUTBOX',
            'otherprofiles' => 'MEMBERSHIPS_ACCESS_OTHER_PROFILES',
            'rating' => 'MEMBERSHIPS_ACCESS_RATING',
            'radiussearch' => 'MEMBERSHIPS_ACCESS_RADIUS_SEARCH',
            'quicksearch' => 'MEMBERSHIPS_ACCESS_QUICK_SEARCH',
            'status' => 'MEMBERSHIPS_ACCESS_STATUS',
//      'wallpage'       => 'MEMBERSHIPS_ACCESS_DETAILED_WALLPAGE',
        );

        return $array;
    }

    // Move users
    function getMembershipsToMove()
    {
        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');
        JArrayHelper::toInteger($cid);

        $query = ' SELECT m.id, m.title, COUNT(p.user_id) AS users'
            . ' FROM #__lovefactory_memberships m'
            . ' LEFT JOIN #__lovefactory_profiles p ON p.membership = m.id'
            . ' WHERE m.id IN (' . implode(',', $cid) . ')'
            . ' GROUP BY m.id';

        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    function moveUsers()
    {
        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');
        $membership_id = JFactory::getApplication()->input->getInt('membership_id');
        JArrayHelper::toInteger($cid);

        $query = ' UPDATE #__lovefactory_profiles'
            . ' SET membership = ' . $membership_id
            . ' WHERE membership IN (' . implode(',', $cid) . ')';
        $this->_db->setQuery($query);
        $this->_db->execute();

        return true;
    }
}
