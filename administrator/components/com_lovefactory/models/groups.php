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

class BackendModelGroups extends JModelLegacy
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
        $limitstart = $mainframe->getUserStateFromRequest($option . '.groups.limitstart', 'limitstart', 0, 'int');

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
        }

        return $this->_data;
    }

    function _buildQuery()
    {
        $orderby = $this->_buildContentOrderBy();
        $where = $this->_buildContentWhere();

        $query = ' SELECT g.*, u.username AS owner_username,'
            . '   COUNT(DISTINCT gm.id) AS members, COUNT(DISTINCT gp.id) AS posts'
            . ' FROM #__lovefactory_groups g'
            . ' LEFT JOIN #__users u ON g.user_id = u.id'
            . ' LEFT JOIN #__lovefactory_group_members gm ON gm.group_id = g.id'
            . ' LEFT JOIN #__lovefactory_group_posts gp ON gp.group_id = g.id'
            //. $where
            . ' GROUP BY g.id, u.username'
            . $orderby;

        return $query;
    }

    function _buildContentOrderBy()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');

        $filter_order = $mainframe->getUserStateFromRequest($option . '.groups.filter_order', 'filter_order', 'title', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option . '.groups.filter_order_Dir', 'filter_order_Dir', 'asc', 'word');

        $orderby = '';

        if (in_array($filter_order, array('g.title', 'u.username',
            'members', 'posts',
            'g.created_at'))) {
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

        $where = ' WHERE 1';

        if ($state != '') {
            $state = ($state == 'P') ? 1 : 0;
            $where .= ' AND m.published = ' . $state;
        }

        return $where;
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
}
