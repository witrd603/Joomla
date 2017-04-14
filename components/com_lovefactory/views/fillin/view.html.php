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

use ThePhpFactory\LoveFactory\Factory;

class FrontendViewFillin extends FactoryView
{
    protected
        $get = array(
        'page',
        'renderer',
        'settings',
    ),
        $js = array('views/edit');

    public function display($tpl = null)
    {
        $key = LoveFactoryApplication::getInstance()->getSettings('gmaps_api_key', '');
        if ($key) {
            JHtml::script('https://maps.googleapis.com/maps/api/js?key=' . $key);
        }

        return parent::display($tpl);
    }

    protected function getRenderer()
    {
        return Factory::buildPageRenderer('editable');
    }

    protected function getSettings()
    {
        return LoveFactoryApplication::getInstance()->getSettings();
    }
}
