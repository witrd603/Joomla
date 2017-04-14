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

class BackendModelPricing extends JModelLegacy
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
        $limitstart = $mainframe->getUserStateFromRequest($option . '.limitstart', 'limitstart', 0, 'int');

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

        $query = ' SELECT p.*, m.title'
            . ' FROM #__lovefactory_pricing p'
            . ' LEFT JOIN #__lovefactory_memberships m ON m.id = p.membership_id'
            . $where
            . $orderby;

        #var_dump($query);

        return $query;
    }

    function _buildContentOrderBy()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');
        $option = JFactory::getApplication()->input->getCmd('option');

        $filter_order = $mainframe->getUserStateFromRequest($option . '.filter_order', 'filter_order', 'title', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option . '.filter_order_Dir', 'filter_order_Dir', 'asc', 'word');

        $orderby = '';

        if (in_array($filter_order, array('p.membership_id',
            'p.price',
            'p.months',
            'p.published',
            'p.is_trial'))) {
            $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;
        }

        return $orderby;
    }

    function _buildContentWhere()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');

        $search = $mainframe->getUserStateFromRequest($option . '.pricing.search', 'search', '', 'cmd');
        $state = $mainframe->getUserStateFromRequest($option . '.pricing.filter_state', 'filter_state', '', 'cmd');
        $membership = $mainframe->getUserStateFromRequest($option . '.pricing.membership', 'membership', -1, 'int');

        $where = array();

        if ($membership > 0) {
            $where[] = ' p.membership_id = ' . $membership;
        }

        if ($state != '') {
            $state = ($state == 'P') ? 1 : 0;
            $where[] = ' p.published = ' . $state;
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
}
