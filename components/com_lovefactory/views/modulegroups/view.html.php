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

class FrontendViewModuleGroups extends JViewLegacy
{
    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->moduleClass = $this->get('ModuleClass');
        $this->Itemid = $this->get('Params')->get('Itemid', JFactory::getApplication()->input->getInt('Itemid'));
        $this->mode = $this->get('Params')->get('mode', 'members');

        parent::display($tpl);
    }
}
