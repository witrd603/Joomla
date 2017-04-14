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

class FrontendModelModuleRegistration extends FrontendModelModule
{
    protected $renderErrorsIndividual = true;

    public function getRenderer()
    {
        $renderer = LoveFactoryPageRenderer::getInstance();

        return $renderer;
    }

    public function getPage($page = 'registration', $mode = 'edit', $loadData = true)
    {
        $page = LoveFactoryPage::getInstance($page, $mode, array(
            'renderErrorsIndividual' => $this->renderErrorsIndividual
        ));

        if ($loadData) {
            $session = JFactory::getSession();
            $context = 'com_lovefactory.profile.signup.data';

            $page->bind($session->get($context, null));

            $session->set($context, null);
        }

        return $page;
    }

    public function getSettings()
    {
        return LoveFactoryApplication::getInstance()->getSettings();
    }
}
