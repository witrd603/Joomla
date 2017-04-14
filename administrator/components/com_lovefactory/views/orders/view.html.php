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

class BackendViewOrders extends LoveFactoryAdminView
{
    protected $items;
    protected $pagination;
    protected $state;
    protected $listOrder;
    protected $listDirn;

    public function display($tpl = null)
    {
        // Initialise variables.
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->filterMemberships = $this->get('FilterMemberships');
        $this->filterStatus = $this->get('FilterStatus');
        $this->filterGateways = $this->get('FilterGateways');

        $this->listOrder = $this->escape($this->state->get('list.ordering'));
        $this->listDirn = $this->escape($this->state->get('list.direction'));

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);
            return false;
        }

        $this->addToolbar();

        JLoader::register('JHtmlLoveFactoryAdministrator', JPATH_COMPONENT_ADMINISTRATOR . DS . 'lib' . DS . 'htmladmin.php');
        JHtml::_('behavior.tooltip');
        JHtml::script('components/com_lovefactory/assets/js/views/backend/orders.js');
        FactoryHtml::stylesheet('admin/main');

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolBarHelper::title(JText::_('COM_LOVEFACTORY_ORDERS_TITLE'));

        JToolBarHelper::editList('order.edit');
        JToolBarHelper::divider();
        JToolBarHelper::custom('orders.complete', 'publish', '', 'COM_LOVEFACTORY_ORDERS_MARK_AS_COMPLETED', true);
        JToolBarHelper::custom('orders.fail', 'unpublish', '', 'COM_LOVEFACTORY_ORDERS_MARK_AS_FAILED', true);
        JToolBarHelper::divider();
        JToolBarHelper::custom('orders.paid', 'apply', '', 'COM_LOVEFACTORY_ORDERS_MARK_AS_PAID', true);
        JToolBarHelper::custom('orders.unpaid', 'cancel', '', 'COM_LOVEFACTORY_ORDERS_MARK_AS_NOT_PAID', true);
        JToolBarHelper::divider();
        JToolBarHelper::deleteList('', 'orders.delete');
    }
}
