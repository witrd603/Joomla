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

jimport('joomla.application.component.view');

class BackendViewReports extends LoveFactoryAdminView
{
    function display($tpl = null)
    {
        JToolBarHelper::title(JText::_('Reports'), 'generic.png');
        JToolBarHelper::editList();
        JToolBarHelper::deleteList(JText::_('Are you sure you want to delete these reports?'), 'delete');

        $reports = $this->get('Items');
        $pagination = $this->get('Pagination');
        $lists = $this->_getViewLists();

        $this->reports = $reports;
        $this->pagination = $pagination;
        $this->lists = $lists;
        $this->settings = LoveFactoryApplication::getInstance()->getSettings();

        $status = array();
        $status[] = JHTML::_('select.option', 0, JText::_('- Select Status -'));
        $status[] = JHTML::_('select.option', 1, JText::_('Not resolved'));
        $status[] = JHTML::_('select.option', 2, JText::_('Resolved'));

        $this->status = $status;

        #JHTML::_('stylesheet', 'main.css', 'components/com_lovefactory/assets/css/');

        parent::display($tpl);
    }

    function &_getViewLists()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');

        $filter_order = $mainframe->getUserStateFromRequest($option . '.filter_order', 'filter_order', 'title', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option . '.filter_order_Dir', 'filter_order_Dir', 'asc', 'word');
        $filter_state = $mainframe->getUserStateFromRequest($option . '.filter_state', 'filter_state', '', 'word');
        $type = $mainframe->getUserStateFromRequest($option . '.type', 'type', '', 'cmd');
        $status = $mainframe->getUserStateFromRequest($option . '.status', 'status', '', 'string');
        $search = $mainframe->getUserStateFromRequest($option . '.search', 'search', '', 'string');
        $search = JString::strtolower($search);

        // table ordering
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;

        // search
        $lists['search'] = $search;

        // types
        $lists['types'] = $this->get('Types');

        // type
        $lists['type'] = $type;

        // status
        $lists['status'] = $status;

        return $lists;
    }
}
