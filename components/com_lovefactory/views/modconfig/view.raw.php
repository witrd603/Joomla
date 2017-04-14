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

class FrontendViewModConfig extends JViewLegacy
{
    function display($tpl = null)
    {
        $this->availableGenders = $this->get('AvailableGenders');
        $this->userConfiguration = $this->get('UserConfiguration');
        $this->moduleId = $this->get('ModuleId');

        parent::display($tpl);
    }
}
