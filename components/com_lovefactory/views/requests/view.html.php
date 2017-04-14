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

class FrontendViewRequests extends FactoryView
{
    protected
        $get = array(
        'items',
        'page',
        'pagination',
        'renderer',
        'settings',
    ),
        $css = array('icons'),
        $behaviors = array('factoryTooltip', 'factoryjQueryUi', 'factoryAjaxAction');

    protected function getRenderer()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $renderer = Factory::buildPageRenderer('viewable');
        $postZone = Factory::buildPostZoneRequests($settings);

        $renderer->setPostZone($postZone);

        return $renderer;
    }
}
