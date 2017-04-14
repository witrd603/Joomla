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

class FrontendModelModuleSearch extends FrontendModelModule
{
    public function getRenderer()
    {
        $renderer = LoveFactoryPageRenderer::getInstance();

        return $renderer;
    }

    public function getPage()
    {
        $params = $this->getParams();
        $type = 'search_' . $params->get('type');

        $page = LoveFactoryPage::getInstance($type, 'search');
        $page->setFormControl('module_search');

        return $page;
    }

    public function getRequest()
    {
        $page = $this->getPage();
        $request = JFactory::getApplication()->input->get($page->getFormControl(), array(), 'array');

        if (!$request) {
            $request = JFactory::getApplication()->input->get('form', array(), 'array');
        }

        return $request;
    }

    public function getType()
    {
        $params = $this->getParams();

        return $params->get('type') == 'quick' ? 'search' : 'advanced';
    }

    public function getJumpToResults()
    {
        $jump = LoveFactoryApplication::getInstance()->getSettings()->search_jump_to_results;

        if (!$jump) {
            return '';
        }

        return '#results';
    }

    public function getItemId()
    {
        $Itemid = $this->getParams()->get('Itemid', '');

        if ('' == $Itemid) {
            return '';
        }

        return '&Itemid=' . $Itemid;
    }
}
