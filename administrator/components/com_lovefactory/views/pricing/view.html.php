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

class BackendViewPricing extends LoveFactoryAdminView
{
    function display($tpl = null)
    {
        require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'settings.php');

        JToolBarHelper::title(JText::_('Pricing'), 'generic.png');
        JToolBarHelper::addNew();
        JToolBarHelper::editList();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::custom('options', 'options', 'options', 'Options', false);
        JToolBarHelper::deleteList(JText::_('Are you sure you want to delete these prices?'));

        $prices = $this->get('Data');
        $pagination = $this->get('Pagination');
        $lists = $this->_getViewLists();
        $settings = new LovefactorySettings();

        $this->prices = $prices;
        $this->pagination = $pagination;
        $this->lists = $lists;
        $this->settings = $settings;

        JHTML::_('behavior.modal');

        parent::display($tpl);
    }

    function &_getViewLists()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');

        $filter_order = $mainframe->getUserStateFromRequest($option . '.pricing.filter_order', 'filter_order', 'title', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option . '.pricing.filter_order_Dir', 'filter_order_Dir', 'asc', 'word');
        $filter_state = $mainframe->getUserStateFromRequest($option . '.pricing.filter_state', 'filter_state', '', 'word');
        $membership = $mainframe->getUserStateFromRequest($option . '.pricing.membership', 'membership', '', 'int');

        // table ordering
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;

        // state
        $lists['state'] = $filter_state;

        // membership
        $lists['membership'] = $membership;

        // memberships
        $lists['memberships'] = array();
        $lists['memberships'][] = JHTML::_('select.option', '-1', JText::_('- Select Membership -'));

        $model = JModelLegacy::getInstance('Price', 'BackendModel');
        $memberships = $model->getMemberships();

        foreach ($memberships as $membership) {
            $lists['memberships'][] = JHTML::_('select.option', $membership->id, $membership->title);
        }

        return $lists;
    }
}
