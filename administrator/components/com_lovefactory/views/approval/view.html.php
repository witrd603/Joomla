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

class BackendViewApproval extends LoveFactoryAdminView
{
    public function display($tpl = null)
    {
        $this->type = $this->get('Type');
        $this->id = $this->get('Id');
        $this->item = $this->get('Item');

        JToolBarHelper::title(JText::_('APPROVAL_TITLE'));
        JToolBarHelper::publish('approve', JText::_('APPROVALS_APPROVE'));
        JToolBarHelper::unpublish('reject', JText::_('APPROVALS_REJECT'));
        JToolBarHelper::divider();
        JToolBarHelper::cancel();

        JHtml::stylesheet('administrator/components/com_lovefactory/assets/css/main.css');

        parent::display($tpl);
    }
}
