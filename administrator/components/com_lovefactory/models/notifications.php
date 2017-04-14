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

class BackendModelNotifications extends JModelList
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'n.id', 'n.subject', 'n.published', 'n.type', 'n.lang_code'
            );
        }

        parent::__construct($config);
    }

    public function getFilterType()
    {
        $options = array();
        $xml = simplexml_load_file(LoveFactoryApplication::getInstance()->getPath('component_administrator') . DS . 'notifications.xml');

        foreach ($xml->notification as $notification) {
            $options[(string)$notification->attributes()->type] = FactoryText::_('notification_' . (string)$notification->attributes()->type);
        }

        return $options;
    }

    protected function getListQuery()
    {
        $query = parent::getListQuery();

        $query->select('n.*')
            ->from('#__lovefactory_notifications n');

        // Select the language
        $query->select('l.title AS language_title')
            ->leftJoin('#__languages l ON l.lang_code = n.lang_code');

        // Filter by search.
        $filter = $this->getState('filter.search', '');
        if ('' != $filter) {
            $query->where('(n.subject LIKE ' . $query->quote('%' . $filter . '%') . ' OR n.body LIKE ' . $query->quote('%' . $filter . '%') . ')');
        }

        // Filter by language.
        $filter = $this->getState('filter.language', '');
        if ('' != $filter) {
            $query->where('n.lang_code = ' . $query->quote($filter));
        }

        // Filter by state.
        $filter = $this->getState('filter.published', '');
        if ('' != $filter) {
            $query->where('n.published = ' . $query->quote($filter));
        }

        // Filter by type.
        $filter = $this->getState('filter.type', '');
        if ('' != $filter) {
            $query->where('n.type = ' . $query->quote($filter));
        }

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering', 'n.subject');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($query->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    protected function populateState($ordering = null, $direction = null)
    {
        // Initialise variables.
        $app = JFactory::getApplication();

        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        $language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
        $this->setState('filter.language', $language);

        $type = $this->getUserStateFromRequest($this->context . '.filter.type', 'filter_type', '');
        $this->setState('filter.type', $type);

        // List state information.
        parent::populateState('n.subject', 'asc');
    }
}
