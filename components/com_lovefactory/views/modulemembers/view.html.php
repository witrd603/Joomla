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

class FrontendViewModuleMembers extends JViewLegacy
{
    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->moduleId = $this->get('ModuleId');
        $this->userFilterGenders = $this->get('UserFilterGenders');
        $this->moduleClass = $this->get('ModuleClass');

        $this->mode = $this->get('Params')->get('mode', 'latest');
        $this->rows = $this->get('Params')->get('rows', 2);
        $this->cols = $this->get('Params')->get('columns', 2);
        $this->Itemid = $this->get('Params')->get('Itemid', JFactory::getApplication()->input->getInt('Itemid'));
        $this->options = $this->get('Options');

        $this->loadAssets();

        parent::display($tpl);
    }

    protected function loadAssets()
    {
        // Initialise
        $settings = new LovefactorySettings();
        $document = JFactory::getDocument();

        // Load html
        require_once(JPATH_SITE . DS . 'components' . DS . 'com_lovefactory' . DS . 'lib' . DS . 'html' . DS . 'html.php');
        require_once(JPATH_SITE . DS . 'components' . DS . 'com_lovefactory' . DS . 'lib' . DS . 'html' . DS . 'module.php');

        FactoryHtml::script('jquery.cookie');
        FactoryHtml::stylesheet('main');

        // Document declarations
        $document->addScriptDeclaration('var root = "' . JURI::root() . '"');

        $this->width = $settings->thumbnail_max_width;
    }
}
