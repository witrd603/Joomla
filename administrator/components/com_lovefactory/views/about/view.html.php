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
jimport('joomla.html.pane');

class BackendViewAbout extends LoveFactoryAdminView
{
    function display($tpl = null)
    {
        $this->about = $this->get('Information');

        JToolBarHelper::title(JText::_('ABOUT_PAGE_TITLE'), 'generic.png');

        parent::display($tpl);
    }
}
