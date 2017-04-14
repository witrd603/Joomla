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
jimport('joomla.html.pane');

class BackendViewFields extends LoveFactoryAdminView
{
    function display($tpl = null)
    {
        JToolBarHelper::title(JText::_('Fields'), 'generic.png');
        JToolBarHelper::back(JText::_('Pages'), 'index.php?option=com_lovefactory&view=pages');
        JToolBarHelper::divider();
        JToolBarHelper::addNew();
        JToolBarHelper::editList();
        JToolBarHelper::divider();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::divider();
        JToolBarHelper::deleteList(JText::_('Are you sure you want to delete these fields?'), 'delete');

        $this->fields = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->lists = $this->_getViewLists();
        $this->state = $this->get('State');

        JHtml::_('behavior.framework');

        parent::display($tpl);
    }

    function &_getViewLists()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');

        $filter_order = $mainframe->getUserStateFromRequest($option . 'filter_order', 'filter_order', 'title', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option . 'filter_order_Dir', 'filter_order_Dir', 'asc', 'word');
        $filter_state = $mainframe->getUserStateFromRequest($option . 'filter_state', 'filter_state', '', 'word');
        $search = $mainframe->getUserStateFromRequest($option . 'search', 'search', '', 'string');
        $search = JString::strtolower($search);

        // table ordering
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;

        // search
        $lists['search'] = $search;

        // state
        $lists['state'] = $filter_state;

        return $lists;
    }
}
