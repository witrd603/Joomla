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

class BackendViewInvoice extends LoveFactoryAdminView
{
    public function display($tpl = null)
    {
        JModelLegacy::addIncludePath(JPATH_COMPONENT_SITE . DS . 'models');
        $model = JModelLegacy::getInstance('Invoice', 'FrontendModel');
        $this->addTemplatePath(JPATH_COMPONENT_SITE . DS . 'views' . DS . 'invoice' . DS . 'tmpl');

        $this->template = $model->getTemplate();

        JHtml::stylesheet('components/com_lovefactory/assets/css/buttons.css');
        JHtml::stylesheet('components/com_lovefactory/assets/css/views/invoice.css');

        $document = JFactory::getDocument();
        $document->addStylesheet(JUri::root() . 'components/com_lovefactory/assets/css/views/invoice.print.css', 'text/css', 'print');

        parent::display($tpl);
    }
}
