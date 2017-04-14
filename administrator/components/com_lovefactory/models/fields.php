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

class BackendModelFields extends JModelList
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'title', 'type', 'required', 'published'
            );
        }

        parent::__construct($config);

        $this->populateState();
    }

    public function getItems()
    {
        $items = parent::getItems();

        return $items;
    }

    protected function getListQuery()
    {
        $query = parent::getListQuery();

        $query->select('f.*')
            ->from($this->getTable()->getTableName() . ' f');

        $this->addQueryFilterPublished($query);
        $this->addQueryFilterSearch($query);
        $this->addQueryFilterFieldType($query);
        $this->addQueryOrder($query);

        return $query;
    }

    public function getTable($name = 'Field', $prefix = 'Table', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    protected function addQueryFilterPublished($query)
    {
        $value = JFactory::getApplication()->getUserStateFromRequest('filter.state', 'filter_state', '', 'string');

        if ('U' == $value) {
            $query->where('f.published = ' . $query->quote(0));
        } elseif ('P' == $value) {
            $query->where('f.published = ' . $query->quote(1));
        }
    }

    protected function addQueryFilterSearch($query)
    {
        $value = JFactory::getApplication()->getUserStateFromRequest('filter.search', 'search', '', 'string');
        $value = trim($value);

        if ('' != $value) {
            $query->where('(f.title LIKE (' . $query->quote('%' . $value . '%') . ') OR f.type LIKE (' . $query->quote('%' . $value . '%') . '))');
        }
    }

    protected function addQueryFilterFieldType($query)
    {
        $pageType = JFactory::getApplication()->input->get('type', '');

        if ('' == $pageType) {
            return $query;
        }

        $availableTypes = $this->getAvailableFieldTypesForPage($pageType);

        if (!$availableTypes) {
            return $query;
        }

        $query->where('f.type IN (' . implode(',', $availableTypes) . ')');
    }

    protected function addQueryOrder($query)
    {
        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering', 'title');
        $orderDirn = $this->state->get('list.direction', 'ASC');

        $query->order($orderCol . ' ' . $orderDirn);
    }

    protected function getAvailableFieldTypesForPage($page)
    {
        jimport('joomla.filesystem.folder');
        $dbo = $this->getDbo();
        $base = LoveFactoryApplication::getInstance()->getPath('component_administrator') . DS . 'lib' . DS . 'fields';
        $folders = JFolder::folders($base);
        $array = array();

        foreach ($folders as $folder) {
            $field = LoveFactoryField::getInstance($folder);

            $whiteList = $field->getAccessPageWhiteList();
            if ($whiteList) {
                if (in_array($page, $whiteList)) {
                    $array[] = $dbo->quote($field->getType());
                }

                continue;
            }

            $blackList = $field->getAccessPageBlackList();
            if ($blackList && in_array($page, $blackList)) {
                continue;
            }

            $array[] = $dbo->quote($field->getType());
        }

        return $array;
    }
}
