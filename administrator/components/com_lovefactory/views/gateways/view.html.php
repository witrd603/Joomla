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

class BackendViewGateways extends LoveFactoryAdminView
{
    protected $items;
    protected $pagination;
    protected $state;
    protected $listOrder;
    protected $listDirn;

    public function display($tpl = null)
    {
        // Parse payment gateways
        $this->getModel()->parseGateways();

        // Initialise variables.
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');

        $this->listOrder = $this->escape($this->state->get('list.ordering'));
        $this->listDirn = $this->escape($this->state->get('list.direction'));

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);
            return false;
        }

        $this->addToolbar();

        JHtml::_('behavior.framework');
        JHtml::script('components/com_lovefactory/assets/js/backend15compat.js');
        JHtml::stylesheet('administrator/components/com_lovefactory/assets/css/main.css');

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolBarHelper::title(JText::_('COM_LOVEFACTORY_GATEWAYS_TITLE'));

        JToolBarHelper::editList('gateway.edit');
        JToolBarHelper::divider();
        JToolBarHelper::publishList('gateways.publish');
        JToolBarHelper::unpublishList('gateways.unpublish');
    }
}
