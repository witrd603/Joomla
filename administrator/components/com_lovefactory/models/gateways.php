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

class BackendModelGateways extends JModelList
{
    var $filters = array('published', 'search');

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'g.title', 'g.published', 'g.element', 'ordering'
            );
        }

        parent::__construct($config);
    }

    public function getTable($type = 'Gateway', $prefix = 'Table', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function parseGateways()
    {
        $gateways = $this->getGateways();

        $this->removeOldGateways();
        $this->addNewGateways($gateways);

        return $gateways;
    }

    protected function getListQuery()
    {
        // Initialise variables.
        $dbo = $this->getDbo();

        // Select the required fields from the table
        $query = $dbo->getQuery(true)
            ->select('g.*')
            ->from('#__lovefactory_gateways g');

        // Filter by published state
        $published = $this->getState('filter.published');

        if (is_numeric($published)) {
            $query->where('g.published = ' . $dbo->quote($published));
        }

        // Filter by search
        $search = $this->getState('filter.search');
        if ('' != $search) {
            $query->where('((g.title LIKE ' . $dbo->quote('%' . $search . '%') . ') OR (g.element LIKE ' . $dbo->quote('%' . $search . '%') . '))');
        }

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');

        $query->order($dbo->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    protected function populateState($ordering = null, $direction = null)
    {
        // Load the filters
        foreach ($this->filters as $filter) {
            $value = $this->getUserStateFromRequest($this->context . '.filter.' . $filter, 'filter_' . $filter);
            $this->setState('filter.' . $filter, $value);
        }

        // List state information.
        parent::populateState('id', 'asc');
    }

    protected function getGateways()
    {
        jimport('joomla.filesystem.folder');

        $base = JPATH_COMPONENT_ADMINISTRATOR . DS . 'payment' . DS . 'gateways';
        $folders = JFolder::folders($base);
        $gateways = array();

        foreach ($folders as $folder) {
            $file = $base . DS . $folder . DS . $folder . '.php';

            if (JFile::exists($file)) {
                $gateways[] = $folder;

                JLoader::register($folder, $file);
            }
        }

        return $gateways;
    }

    protected function addNewGateways($gateways)
    {
        $dbo = JFactory::getDbo();
        $count = 0;

        $query = $dbo->getQuery(true)
            ->select('g.id, g.element')
            ->from('#__lovefactory_gateways g');

        $results = $dbo->setQuery($query)
            ->loadObjectList('element');

        foreach ($gateways as $gateway) {
            if (!array_key_exists($gateway, $results)) {
                $table = JTable::getInstance('Gateway', 'Table');

                $table->title = $gateway;
                $table->element = $gateway;

                if (!$table->check()) {
                    continue;
                }

                if ($table->store()) {
                    $count++;
                }
            }
        }

        if ($count) {
            JFactory::getApplication()->enqueueMessage(JText::plural('COM_LOVEFACTORY_GATEWAYS_ADDED_N_GATEWAYS', $count), 'notice');
        }
    }

    protected function removeOldGateways()
    {
        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('g.*')
            ->from('#__lovefactory_gateways g');
        $gateways = $dbo->setQuery($query)
            ->loadObjectList();

        foreach ($gateways as $gateway) {
            if (empty($gateway->element)) {
                $table = JTable::getInstance('Gateway', 'Table');
                $table->delete($gateway->id);
            }
        }
    }
}
