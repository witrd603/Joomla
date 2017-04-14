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

class FrontendViewModuleShoutBox extends JViewLegacy
{
    protected $restriction;
    protected $user;

    public function display($tpl = null)
    {
        $this->moduleId = $this->get('ModuleId');
        $this->moduleClass = $this->get('ModuleClass');

        $this->enabled = $this->get('Enabled');
        $this->items = $this->get('Messages');
        $this->settings = $this->get('Settings');
        $this->lastUpdate = $this->get('LastUpdate');

        $this->user = JFactory::getUser();
        $this->restriction = $this->get('Restriction');

        $this->loadAssets();

        parent::display($tpl);
    }

    protected function loadAssets()
    {
        FactoryHtml::script('moduleshoutbox');
    }
}
