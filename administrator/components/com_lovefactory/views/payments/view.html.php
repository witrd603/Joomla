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

class BackendViewPayments extends LoveFactoryAdminView
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
        $this->filterStatus = $this->get('FilterStatus');
        $this->filterGateway = $this->get('FilterGateway');

        $this->listOrder = $this->escape($this->state->get('list.ordering'));
        $this->listDirn = $this->escape($this->state->get('list.direction'));

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);
            return false;
        }

        JHtml::_('behavior.framework');
        JLoader::register('JHtmlLoveFactoryAdministrator', JPATH_COMPONENT_ADMINISTRATOR . DS . 'lib' . DS . 'htmladmin.php');
        JHtml::_('behavior.tooltip');
        JHtml::script('components/com_lovefactory/assets/js/backend15compat.js');

        $document = JFactory::getDocument();
        $document->addStyleDeclaration('.icon-32-gateways { background-image: url(' . JURI::root() . '/components/com_lovefactory/assets/images/creditcard_paypal.png); background-position: center; width: 48px !important; }');

        FactoryHtml::stylesheet('admin/main');

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolBarHelper::title(JText::_('COM_LOVEFACTORY_PAYMENTS_TITLE'));

        JToolBarHelper::custom('payments.gateways', 'gateways', 'gateways', JText::_('COM_LOVEFACTORY_PAYMENTS_GATEWAYS'), false);
        JToolBarHelper::divider();
        JToolBarHelper::editList('payment.edit');
        JToolBarHelper::divider();
        JToolBarHelper::custom('payments.complete', 'publish', '', 'Mark as Completed', true);
        JToolBarHelper::custom('payments.fail', 'unpublish', '', 'Mark as Failed', true);
        JToolBarHelper::divider();
        JToolBarHelper::deleteList('', 'payments.delete');
    }
}
