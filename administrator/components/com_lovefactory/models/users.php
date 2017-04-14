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

class BackendModelUsers extends JModelLegacy
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
        $limitstart = $mainframe->getUserStateFromRequest($option . '.users.limitstart', 'limitstart', 0, 'int');

        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }

    function getData()
    {
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_data;
    }

    function _buildQuery()
    {
        $orderby = $this->_buildContentOrderBy();
        $where = $this->_buildContentWhere();

        $query = ' SELECT p.*, u.username, s.title AS membership_title, s.end_membership'
            . ' FROM #__lovefactory_profiles p'
            . ' LEFT JOIN #__users u ON u.id = p.user_id'
            . ' LEFT JOIN #__lovefactory_memberships_sold s ON s.id = p.membership_sold_id'
            . $where
            . $orderby;

        return $query;
    }

    function _buildContentOrderBy()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');

        $filter_order = $mainframe->getUserStateFromRequest($option . '.users.filter_order', 'filter_order', 'title', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option . '.users.filter_order_Dir', 'filter_order_Dir', 'asc', 'word');

        $orderby = '';
        $filters = array(
            'u.username',
            's.membership_id',
            's.end_membership',
            'p.banned',
            'p.online',
            'p.filled',
            'p.date',
        );

        if (in_array($filter_order, $filters)) {
            $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;
        }

        return $orderby;
    }

    function _buildContentWhere()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');
        $dbo = $this->getDbo();

        $search = $mainframe->getUserStateFromRequest($option . '.users.search', 'search', '', 'cmd');
        $state = $mainframe->getUserStateFromRequest($option . '.users.filter_state', 'filter_state', '', 'cmd');
        $membership = $mainframe->getUserStateFromRequest($option . '.users.membership', 'membership', -1, 'int');
        $banned = $mainframe->getUserStateFromRequest($option . '.users.banned', 'banned', -1, 'int');

        $where = array();

        if ($search != '') {
            $where[] = ' UPPER(u.username) LIKE UPPER(' . $dbo->quote('%' . $search . '%') . ')';
        }

        if ($membership > 0) {
            $defaultMembership = $this->getDefaultMembership();

            if ($membership == $defaultMembership->id) {
                $where[] = ' (s.membership_id = ' . $dbo->q($membership) . ' OR p.membership_sold_id = ' . $dbo->q(0) . ')';
            } else {
                $where[] = ' s.membership_id = ' . $membership;
            }
        }

        if (in_array($banned, array(0, 1))) {
            $where[] = ' p.banned = ' . $banned;
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

    public function getDefaultMembership()
    {
        $table = JTable::getInstance('Membership', 'Table');
        $table->loadDefault();

        return $table;
    }
}
