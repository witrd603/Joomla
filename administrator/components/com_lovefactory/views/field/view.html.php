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

class BackendViewField extends LoveFactoryAdminView
{
    public function display($tpl = null)
    {
        // Initialise variables.
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');

        $this->addToolbar();

        JHtml::_('behavior.tooltip');

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolBarHelper::title('Field');

        JToolBarHelper::apply('field.apply');
        JToolBarHelper::save('field.save');
        JToolBarHelper::cancel('field.cancel');
    }
}
