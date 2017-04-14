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

class BackendViewPayment extends LoveFactoryAdminView
{
    public function display($tpl = null)
    {
        // Initialiase variables.
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);
            return false;
        }

        $this->addToolbar();

        JHtml::_('behavior.framework');
        JHtml::_('behavior.tooltip');
        JHtml::_('behavior.formvalidation');
        JHtml::script('components/com_lovefactory/assets/js/backend15compat.js');
        FactoryHtml::stylesheet('admin/main');

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolBarHelper::title('Payment');

        if (!in_array($this->item->status, array(20, 30))) {
            JToolBarHelper::apply('payment.apply');
            JToolBarHelper::save('payment.save');
        }

        JToolBarHelper::cancel('payment.cancel');
    }
}
