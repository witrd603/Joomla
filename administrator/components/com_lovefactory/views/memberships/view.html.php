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

class BackendViewMemberships extends LoveFactoryAdminView
{
    function display($tpl = null)
    {
        JToolBarHelper::title(JText::_('MEMBERSHIPS_PAGE_TITLE'), 'generic.png');

        JToolBarHelper::back('MEMBERSHIPS_PRICING', 'index.php?option=com_lovefactory&view=pricing');
        JToolBarHelper::divider();
        JToolBarHelper::makeDefault('setdefault');
        JToolBarHelper::divider();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::divider();
        JToolBarHelper::addNew();
        JToolBarHelper::editList();
//    JToolBarHelper::custom('options', 'options', 'options', 'Options', false);
        JToolBarHelper::deleteList(JText::_('MEMBERSHIPS_DELETE_WARNING'));

        $settings = new LovefactorySettings();
        $memberships = $this->get('Data');
        $pagination = $this->get('Pagination');
        $lists = $this->_getViewLists();
        $ordering = ($lists['order'] == 'm.ordering');
        $shoutbox = $this->get('Shoutbox');

        $this->memberships = $memberships;
        $this->pagination = $pagination;
        $this->lists = $lists;
        $this->ordering = $ordering;
        $this->shoutbox = $shoutbox;
        $this->settings = $settings;

        if ($this->_layout == 'options') {
            $access = $this->get('Access');
            $this->access = $access;
        }

        JHTML::_('behavior.modal');

        parent::display($tpl);
    }

    function &_getViewLists()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');

        $filter_order = $mainframe->getUserStateFromRequest($option . '.memberships.filter_order', 'filter_order', 'title', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option . '.memberships.filter_order_Dir', 'filter_order_Dir', 'asc', 'word');
        $filter_state = $mainframe->getUserStateFromRequest($option . '.memberships.filter_state', 'filter_state', '', 'word');

        // table ordering
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;

        // state
        $lists['state'] = $filter_state;

        return $lists;
    }

    protected function parseUnlimited($value)
    {
        if (-1 == $value) {
            return JText::_('unlimited');
        }

        return $value;
    }
}
