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

class BackendViewApprovals extends LoveFactoryAdminView
{
    function display($tpl = null)
    {
        JToolBarHelper::title(JText::_('Approvals'), 'generic.png');

        JToolBarHelper::editList('review', JText::_('APPROVALS_REVIEW'));
        JToolBarHelper::divider();
        JToolBarHelper::publishList('approve', JText::_('APPROVALS_APPROVE'));
        JToolBarHelper::unpublishList('reject', JText::_('APPROVALS_REJECT'));

        $this->settings = new LovefactorySettings();
        $this->items = $this->get('Data');
        $this->pagination = $this->get('Pagination');
        $this->lists = $this->_getViewLists();

        parent::display($tpl);
    }

    function &_getViewLists()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');

        $filter_order = $mainframe->getUserStateFromRequest($option . '.groups.filter_order', 'filter_order', 'title', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option . '.groups.filter_order_Dir', 'filter_order_Dir', 'asc', 'word');
        $filter_state = $mainframe->getUserStateFromRequest($option . '.groups.filter_state', 'filter_state', '', 'word');

        // table ordering
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;

        // state
        $lists['state'] = $filter_state;

        return $lists;
    }
}
