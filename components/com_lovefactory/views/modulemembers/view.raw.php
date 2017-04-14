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
    public function display($tpl = 'items')
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        $this->mode = $this->get('Params')->get('mode', 'latest');
        $this->rows = $this->get('Params')->get('rows', 1);
        $this->cols = $this->get('Params')->get('columns', 1);
        $this->Itemid = $this->get('Params')->get('Itemid', JFactory::getApplication()->input->getInt('Itemid'));
        $this->options = $this->get('Options');

        $this->loadAssets();

        parent::display($tpl);
    }

    protected function loadAssets()
    {
        // Load html
        require_once(JPATH_SITE . DS . 'components' . DS . 'com_lovefactory' . DS . 'lib' . DS . 'html' . DS . 'html.php');
    }
}
