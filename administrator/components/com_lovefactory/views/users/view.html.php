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

class BackendViewUsers extends LoveFactoryAdminView
{
    protected $users;
    protected $pagination;
    protected $lists;
    protected $settings;
    protected $defaultMembership;

    public function display($tpl = null)
    {
        require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'settings.php');

        JToolBarHelper::title(JText::_('Users'), 'generic.png');

        //JToolBarHelper::custom('verifyusers', 'database', '', 'Verify users', false);
//    JToolBarHelper::divider();

        $settings = new LovefactorySettings();

        if (array_intersect(JFactory::getUser()->groups, $settings->create_profile_admin_groups)) {
            JToolbarHelper::addNew('create');
        }

        JToolBarHelper::editList();
        JToolBarHelper::divider();
        JToolBarHelper::publishList('ban', JText::_('Ban'));
        JToolBarHelper::unpublishList('unban', JText::_('Unban'));
        JToolBarHelper::divider();
        JToolBarHelper::custom('fill', 'publish', 'publish', 'Mark profiles as filled', true);
        JToolBarHelper::custom('unfill', 'unpublish', 'unpublish', 'Clear filled profiles', true);
        JToolBarHelper::deleteList(JText::_('Are you sure you want to delete these users?'), 'delete');

        $users = $this->get('Data');
        $pagination = $this->get('Pagination');
        $lists = $this->_getViewLists();

        $this->users = $users;
        $this->pagination = $pagination;
        $this->lists = $lists;
        $this->settings = $settings;
        $this->defaultMembership = $this->get('DefaultMembership');

        JHTML::_('behavior.modal');

        parent::display($tpl);
    }

    public function &_getViewLists()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');

        $filter_order = $mainframe->getUserStateFromRequest($option . '.users.filter_order', 'filter_order', 'title', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option . '.users.filter_order_Dir', 'filter_order_Dir', 'asc', 'word');
        $filter_state = $mainframe->getUserStateFromRequest($option . '.users.filter_state', 'filter_state', '', 'word');
        $membership = $mainframe->getUserStateFromRequest($option . '.users.membership', 'membership', '', 'int');
        $banned = $mainframe->getUserStateFromRequest($option . '.users.banned', 'banned', '', 'int');
        $search = $mainframe->getUserStateFromRequest($option . '.users.search', 'search', '', 'string');
        $search = JString::strtolower($search);

        // table ordering
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;

        // search
        $lists['search'] = $search;

        // state
        $lists['state'] = $filter_state;

        // membership
        $lists['membership'] = $membership;

        // banned
        $lists['banned'] = $banned;

        $lists['bans'] = array();
        $lists['bans'][] = JHTML::_('select.option', '-1', JText::_('- Select Banned -'));
        $lists['bans'][] = JHTML::_('select.option', '0', JText::_('Not banned'));
        $lists['bans'][] = JHTML::_('select.option', '1', JText::_('Banned'));

        // memberships
        $settings = new LovefactorySettings();

        $lists['memberships'] = array();
        $lists['memberships'][] = JHTML::_('select.option', '-1', JText::_('- Select Membership -'));

        $model = JModelLegacy::getInstance('Price', 'BackendModel');
        $memberships = $model->getMemberships(0);

        foreach ($memberships as $membership) {
            $lists['memberships'][] = JHTML::_('select.option', $membership->id, $membership->title);
        }

        return $lists;
    }
}
