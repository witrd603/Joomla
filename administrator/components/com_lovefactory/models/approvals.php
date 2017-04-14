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

class BackendModelApprovals extends JModelLegacy
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
        $limitstart = $mainframe->getUserStateFromRequest($option . '.approvals.limitstart', 'limitstart', 0, 'int');

        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }

    // List
    function getData()
    {
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            if (!$query) {
                $this->_data = array();
            } else {
                $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
            }
        }

        return $this->_data;
    }

    function _buildQuery()
    {
        $dbo = $this->getDbo();
        $settings = new LovefactorySettings();
        $query = array();

        // Photos
        if ($settings->approval_photos) {
            $query[] = '(SELECT ' . $dbo->quote('photo') . ' AS type, p.user_id AS user_id, u.username AS username, p.id AS item_id, p.date_added AS created_at'
                . ' FROM #__lovefactory_photos p'
                . ' LEFT JOIN #__users u ON u.id = p.user_id'
                . ' WHERE p.approved = 0)';
        }

        // Videos
        if ($settings->approval_videos) {
            $query[] = '(SELECT ' . $dbo->quote('video') . ' AS type, v.user_id AS user_id, u.username AS username, v.id AS item_id, v.date_added AS created_at'
                . ' FROM #__lovefactory_videos v'
                . ' LEFT JOIN #__users u ON u.id = v.user_id'
                . ' WHERE v.approved = 0)';
        }

        // Profile Comments
        if ($settings->approval_comments) {
            $query[] = '(SELECT ' . $dbo->quote('profilecomment') . ' AS type, c.user_id AS user_id, u.username AS username, c.id AS item_id, c.created_at AS created_at'
                . ' FROM #__lovefactory_item_comments c'
                . ' LEFT JOIN #__users u ON u.id = c.user_id'
                . ' WHERE c.approved = 0 AND c.item_type = ' . $dbo->quote('profile') . ')';
        }

        // Photo Comments
        if ($settings->approval_comments_photo) {
            $query[] = '(SELECT ' . $dbo->quote('photocomment') . ' AS type, c.user_id AS user_id, u.username AS username, c.id AS item_id, c.created_at AS created_at'
                . ' FROM #__lovefactory_item_comments c'
                . ' LEFT JOIN #__users u ON u.id = c.user_id'
                . ' WHERE c.approved = 0 AND c.item_type = ' . $dbo->quote('photo') . ')';
        }

        // Video Comments
        if ($settings->approval_comments_video) {
            $query[] = '(SELECT ' . $dbo->quote('videocomment') . ' AS type, c.user_id AS user_id, u.username AS username, c.id AS item_id, c.created_at AS created_at'
                . ' FROM #__lovefactory_item_comments c'
                . ' LEFT JOIN #__users u ON u.id = c.user_id'
                . ' WHERE c.approved = 0 AND c.item_type = ' . $dbo->quote('video') . ')';
        }

        // Messages
        if ($settings->approval_messages) {
            $query[] = '(SELECT ' . $dbo->quote('message') . ' AS type, m.sender_id AS user_id, u.username AS username, m.id AS item_id, m.date AS created_at'
                . ' FROM #__lovefactory_messages m'
                . ' LEFT JOIN #__users u ON u.id = m.sender_id'
                . ' WHERE m.approved = 0)';

            $query[] = '(SELECT ' . $dbo->quote('request') . ' AS type, f.sender_id AS user_id, u.username AS username, f.id AS item_id, f.date AS created_at'
                . ' FROM #__lovefactory_friends f'
                . ' LEFT JOIN #__users u ON u.id = f.sender_id'
                . ' WHERE f.approved = 0 AND f.pending = 1)';
        }

        // Groups
        if ($settings->approval_groups) {
            $query[] = '(SELECT ' . $dbo->quote('group') . ' AS type, g.user_id AS user_id, u.username AS username, g.id AS item_id, g.created_at AS created_at'
                . ' FROM #__lovefactory_groups g'
                . ' LEFT JOIN #__users u ON u.id = g.user_id'
                . ' WHERE g.approved = 0)';
        }

        // Groups Threads
        if ($settings->approval_group_threads) {
            $query[] = '(SELECT ' . $dbo->quote('threadgroup') . ' AS type, t.user_id AS user_id, u.username AS username, t.id AS item_id, t.created_at AS created_at'
                . ' FROM #__lovefactory_group_threads t'
                . ' LEFT JOIN #__users u ON u.id = t.user_id'
                . ' WHERE t.approved = 0)';
        }

        // Groups Post
        if ($settings->approval_groups_posts) {
            $query[] = '(SELECT ' . $dbo->quote('postgroup') . ' AS type, p.user_id AS user_id, u.username AS username, p.id AS item_id, p.created_at AS created_at'
                . ' FROM #__lovefactory_group_posts p'
                . ' LEFT JOIN #__users u ON u.id = p.user_id'
                . ' WHERE p.approved = 0)';
        }

        // Profile
        if ($settings->approval_profile) {
            $query[] = '(SELECT ' . $dbo->quote('profile') . ' AS type, p.user_id AS user_id, u.username AS username, p.id AS item_id, p.created_at AS created_at'
                . ' FROM #__lovefactory_profile_updates p'
                . ' LEFT JOIN #__users u ON u.id = p.user_id'
                . ' WHERE p.pending = 1 AND p.approved = 0)';
        }

        if (!$query) {
            return false;
        }

        return implode(" UNION ", $query) . $this->_buildContentOrderBy();
    }

    function _buildContentOrderBy()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');

        $filter_order = $mainframe->getUserStateFromRequest($option . '.approvals.filter_order', 'filter_order', 'title', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option . '.approvals.filter_order_Dir', 'filter_order_Dir', 'asc', 'word');

        $orderby = '';

        if (in_array($filter_order, array('username', 'type', 'created_at'))) {
            $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;
        } else {
            $orderby = ' ORDER BY created_at DESC';
        }

        return $orderby;
    }

    function getTotal()
    {
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            if (!$query) {
                $this->_total = 0;
            } else {
                $this->_total = $this->_getListCount($query);
            }
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
}
