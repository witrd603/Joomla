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

class BackendModelReports extends JModelList
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'u.username', 'r.date', 'r.status'
            );
        }

        parent::__construct($config);

        $this->populateState();
    }

    protected function getListQuery()
    {
        $query = parent::getListQuery();

        $query->select('r.*')
            ->from($this->getTable()->getTableName() . ' r');

        // Select reported user.
        $query->select('u.username')
            ->leftJoin('#__users u ON u.id = r.user_id');

        $this->addQueryFilterState($query);
        $this->addQueryFilterSearch($query);
        $this->addQueryOrder($query);

        return $query;
    }

    public function getTable($name = 'Report', $prefix = 'Table', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    public function getFilterStatus()
    {
        $array = array(
            '' => FactoryText::_('reports_filter_status_option_blank'),
            0 => FactoryText::_('reports_filter_status_option_pending'),
            1 => FactoryText::_('reports_filter_status_option_resolved'),
        );

        return $array;
    }

    protected function addQueryFilterState($query)
    {
        $value = JFactory::getApplication()->getUserStateFromRequest('filter.state', 'status', '', 'string');

        if ('' != $value) {
            $query->where('r.status = ' . $query->quote($value));
        }
    }

    protected function addQueryFilterSearch($query)
    {
        $value = JFactory::getApplication()->getUserStateFromRequest('filter.search', 'search', '', 'string');
        $value = trim($value);

        if ('' != $value) {
            $query->where('(r.element LIKE (' . $query->quote('%' . $value . '%') . ') OR r.type LIKE (' . $query->quote('%' . $value . '%') . '))');
        }
    }

    protected function addQueryOrder($query)
    {
        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering', 'date');
        $orderDirn = $this->state->get('list.direction', 'DESC');

        $query->order($orderCol . ' ' . $orderDirn);
    }
}
