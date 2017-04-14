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

class BackendModelPayments extends JModelList
{
    var $filters = array('search', 'membership', 'paid', 'status', 'gateway');

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'p.id', 'p.refnumber', 'p.received_at', 'u.username', 'p.order_id', 'p.gateway', 'p.amount', 'p.status'
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
            ->select('p.*')
            ->from('#__lovefactory_payments p');

        // Select the gateway
        $query->select('g.title AS gateway')
            ->leftJoin('#__lovefactory_gateways g ON g.id = p.gateway');

        // Select the username
        $query->select('u.username')
            ->leftJoin('#__users u ON u.id = p.user_id');

        // Select the order
        $query->select('o.id AS order_id')
            ->leftJoin('#__lovefactory_orders o ON o.id = p.order_id');

        // Filter by status
        $status = $this->getState('filter.status');
        if (is_numeric($status)) {
            $query->where('p.status = ' . $dbo->quote($status));
        }

        // Filter by gateway
        $gateway = $this->getState('filter.gateway');
        if (is_numeric($gateway)) {
            $query->where('p.gateway = ' . $dbo->quote($gateway));
        }

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('p.id = ' . (int)substr($search, 3));
            } else {
                $search = $dbo->quote('%' . $dbo->escape($search, true) . '%');
                $query->where('(p.refnumber LIKE ' . $search . ') OR (u.username LIKE ' . $search . ')');
            }
        }

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');

        $query->order($dbo->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getTable($type = 'Payment', $prefix = 'Table', $config = array())
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
        parent::populateState('received_at', 'desc');
    }

    public function getFilterStatus()
    {
        $table = $this->getTable();

        return $table->getStatusLabel();
    }

    public function getFilterGateway()
    {
        $model = JModelLegacy::getInstance('Orders', 'BackendModel');

        return $model->getFilterGateways();
    }
}
