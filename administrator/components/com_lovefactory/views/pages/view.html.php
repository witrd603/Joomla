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

class BackendViewPages extends LoveFactoryAdminView
{
    function display($tpl = null)
    {
        JToolBarHelper::title(JText::_('Pages'), 'generic.png');
        JToolBarHelper::back(JText::_('Fields'), 'index.php?option=com_lovefactory&view=fields');
        JToolBarHelper::divider();
        JToolBarHelper::editList();

        $pages = $this->get('Data');
        $pagination = $this->get('Pagination');
        $info = $this->get('Info');
        $lists = $this->_getViewLists();

        $this->pages = $pages;
        $this->pagination = $pagination;
        $this->info = $info;
        $this->lists = $lists;

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
