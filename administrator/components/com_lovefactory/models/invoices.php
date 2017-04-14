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

class BackendModelInvoices extends JModelList
{
    var $filters = array(
        'search',
        'from',
        'to'
    );

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'u.username', 'i.membership', 'i.price', 'i.total', 'i.issued_at', 'i.vat_value'
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
            ->select('i.*')
            ->from('#__lovefactory_invoices i');

        // Select the username
        $query->select('u.username')
            ->leftJoin('#__users u ON u.id = i.user_id');

        // Filter by search
        $search = $this->getState('filter.search');
        if ('' != $search) {
            $query->where('(UPPER(i.membership) LIKE UPPER(' . $dbo->quote('%' . $search . '%') . ') OR UPPER(u.username) LIKE UPPER(' . $dbo->quote('%' . $search . '%') . '))');
        }

        // Filter by from
        $from = $this->getState('filter.from');
        if ('' != $from) {
            $from = JFactory::getDate(JFactory::getDate($from)->format('Y-m-d 00:00:00'));
            $query->where('i.issued_at >= ' . $dbo->quote($from->toUnix()));
        }

        // Filter by to
        $to = $this->getState('filter.to');
        if ('' != $to) {
            $to = JFactory::getDate(JFactory::getDate($to)->format('Y-m-d 23:59:59'));
            $query->where('i.issued_at <= ' . $dbo->quote($to->toUnix()));
        }

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');

        $query->order($dbo->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getTable($type = 'Invoice', $prefix = 'Table', $config = array())
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
        parent::populateState('i.issued_at', 'desc');
    }

    public function getDataForExport()
    {
        $this->populateState();

        $dbo = JFactory::getDbo();
        $query = $this->getListQuery();

        $items = $dbo->setQuery($query)
            ->loadAssocList();

        foreach ($items as &$item) {
            $item['buyer'] = strip_tags(str_replace('<br />', ',', $item['buyer']));
            $item['seller'] = strip_tags(str_replace('<br />', ',', $item['seller']));

            $item['buyer'] = str_replace("\n", '', $item['buyer']);
            $item['buyer'] = str_replace("\r", ', ', $item['buyer']);

            $item['issued_at'] = JHtml::_('date', $item['issued_at'], 'Y-m-d H:i:s');
        }

        array_unshift($items, array(
            JText::_('COM_LOVEFACTORY_INVOICES_LIST_ID'),
            JText::_('COM_LOVEFACTORY_INVOICES_LIST_USER_ID'),
            JText::_('COM_LOVEFACTORY_INVOICES_LIST_SELLER'),
            JText::_('COM_LOVEFACTORY_INVOICES_LIST_BUYER'),
            JText::_('COM_LOVEFACTORY_INVOICES_LIST_MEMBERSHIP'),
            JText::_('COM_LOVEFACTORY_INVOICES_LIST_PRICE'),
            JText::_('COM_LOVEFACTORY_INVOICES_LIST_CURRENCY'),
            JText::_('COM_LOVEFACTORY_INVOICES_LIST_VAT_RATE'),
            JText::_('COM_LOVEFACTORY_INVOICES_LIST_VAT_VALUE'),
            JText::_('COM_LOVEFACTORY_INVOICES_LIST_TOTAL'),
            JText::_('COM_LOVEFACTORY_INVOICES_LIST_ISSUED_AT'),
            JText::_('COM_LOVEFACTORY_INVOICES_LIST_USERNAME'),
        ));

        return $items;
    }
}
