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

class BackendModelPages extends JModelLegacy
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

        $query = ' SELECT *'
            . ' FROM #__lovefactory_pages'
            . $where
            . $orderby;

        return $query;
    }

    function _buildContentOrderBy()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');

        $filter_order = $mainframe->getUserStateFromRequest($option . '.filter_order', 'filter_order', 'title', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option . '.filter_order_Dir', 'filter_order_Dir', 'asc', 'word');

        $orderby = '';

        if (in_array($filter_order, array('title'))) {
            $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;
        }

        return $orderby;
    }

    function _buildContentWhere()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');
        $dbo = $this->getDbo();

        $search = $mainframe->getUserStateFromRequest($option . '.search', 'search', '', 'cmd');
        $state = $mainframe->getUserStateFromRequest($option . '.filter_state', 'filter_state', '', 'cmd');

        $where = array();

        if ($search != '') {
            $where[] = ' UPPER(title) LIKE UPPER(' . $dbo->quote('%' . $search . '%') . ')';
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

    function getInfo()
    {
        $array = array(
            1 => JText::_('Fields used in the registration form.'),
            2 => JText::_('Fields used when editing the profile.'),
            3 => JText::_('Fields used in the quick search form.'),
            4 => JText::_('Fields used in the advanced search form.'),
            5 => JText::_('Fields used to display the profiles for the returned results from search.'),
            6 => JText::_('Fields used to display the profile.'),
            7 => JText::_('Fields used to display the profiles of friends in friends view.'),
            8 => JText::_('Fields used in the Profile Fillin form.'),
            9 => JText::_('Fields used for more info about members on Search Radius and Full Members Map.'),
        );

        return $array;
    }

    public function removeFieldFromPages($id)
    {
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->select('p.fields, p.id')
            ->from('#__lovefactory_pages p');
        $dbo->setQuery($query);
        $pages = $dbo->loadObjectList();

        foreach ($pages as $page) {
            if (preg_match('/(^|#)(\d+)_(\d+)_' . $id . '(#|$)/', $page->fields, $matches)) {
                $replace_with = ($matches[1] == '#' && $matches[4] == '#') ? '#' : '';
                $fields = str_replace($matches[0], $replace_with, $page->fields);

                $query = $dbo->getQuery(true)
                    ->update('#__lovefactory_pages')
                    ->set('fields = ' . $dbo->quote($fields))
                    ->where('id = ' . $dbo->quote($page->id));

                $dbo->setQuery($query);
                $dbo->execute();
            }
        }
    }
}
