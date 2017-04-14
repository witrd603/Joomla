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

class FrontendViewInvoice extends JViewLegacy
{
    public function display($tpl = null)
    {
        $this->template = $this->get('Template');

        JHtml::stylesheet('components/com_lovefactory/assets/css/buttons.css');
        JHtml::stylesheet('components/com_lovefactory/assets/css/views/invoice.css');

        $document = JFactory::getDocument();
        $document->addStylesheet('components/com_socialfactory/assets/css/views/invoice.print.css', 'text/css', 'print');

        parent::display($tpl);
    }
}
