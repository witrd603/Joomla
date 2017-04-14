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

jimport('joomla.application.component.modellist');

class BackendModelOrders extends JModelList
{
    var $filters = array('search', 'membership', 'paid', 'status', 'gateway');

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'o.id', 'o.title', 'u.username', 'm.title', 'o.gateway', 'o.amount', 'o.created_at', 'o.status', 'o.paid'
            );
        }

        parent::__construct($config);
    }

    protected function getListQuery()
    {
        // Initialise variables.
        $dbo = $this->getDbo();

        // Select the required fields from the table
        $query = $dbo->getQuery(true)
            ->select('o.*')
            ->from('#__lovefactory_orders o');

        // Select the username
        $query->select('u.username')
            ->leftJoin('#__users u ON u.id = o.user_id');

        // Select the membership
        $query->select('m.title AS membership_title')
            ->leftJoin('#__lovefactory_memberships m ON m.id = o.membership_id');

        // Select the gateway
        $query->select('g.title AS gateway')
            ->leftJoin('#__lovefactory_gateways g ON g.id = o.gateway');

        // Filter by memberhsip
        $membership = $this->getState('filter.membership');
        if (is_numeric($membership)) {
            $query->where('o.membership_id = ' . $dbo->quote($membership));
        }

        // Filter by gateway
        $gateway = $this->getState('filter.gateway');
        if (is_numeric($gateway)) {
            $query->where('o.gateway = ' . $dbo->quote($gateway));
        }

        // Filter by status
        $status = $this->getState('filter.status');
        if (is_numeric($status)) {
            $query->where('o.status = ' . $dbo->quote($status));
        }

        // Filter by paid
        $paid = $this->getState('filter.paid');
        if (is_numeric($paid)) {
            $query->where('o.paid = ' . $dbo->quote($paid));
        }

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('o.id = ' . (int)substr($search, 3));
            } else {
                $search = $dbo->quote('%' . $dbo->escape($search, true) . '%');
                $query->where('(o.title LIKE ' . $search . ') OR (u.username LIKE ' . $search . ')');
            }
        }

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');

        $query->order($dbo->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getTable($type = 'Order', $prefix = 'Table', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        // Load the filters
        foreach ($this->filters as $filter) {
            $value = $this->getUserStateFromRequest($this->context . '.filter.' . $filter, 'filter_' . $filter);
            $this->setState('filter.' . $filter, $value);
        }

        // List state information.
        parent::populateState('created_at', 'desc');
    }

    public function getFilterMemberships()
    {
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->select('m.title AS text, m.id AS value')
            ->from('#__lovefactory_memberships m')
            ->order('m.ordering ASC');

        $results = $dbo->setQuery($query)
            ->loadObjectList();

        return $results;
    }

    public function getFilterStatus()
    {
        $table = $this->getTable();

        return $table->getStatusLabel();
    }

    public function getFilterGateways()
    {
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->select('g.title AS text, g.id AS value')
            ->from('#__lovefactory_gateways g')
            ->order('g.title ASC');

        $results = $dbo->setQuery($query)
            ->loadObjectList();

        return $results;
    }
}
