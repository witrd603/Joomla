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

class FrontendViewGroupedMembers extends FactoryView
{
    protected
        $get = array(
        'items',
        'pagination',
        'rendererResults',
        'pageResults',
        'columns',
        'ads'
    ),
        $behaviors = array('factoryTooltip', 'factoryjQueryUi', 'factoryAjaxAction'),
        $extraTplViewPaths = array('results');

    protected function getRendererResults()
    {
        $renderer = Factory::buildPageRenderer('viewable');
        $postZone = Factory::buildPostZoneResults();

        $renderer->setPostZone($postZone);

        return $renderer;
    }
}
