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

class FrontendViewResults extends FactoryView
{
    protected
        $get = array(
        'items',
        'pagination',
        'rendererResults',
        'pageResults',
        'limitedResults',
        'filter',
        'filterDir',
        'columns',
        'ads'
    );

    public function __construct($config = array())
    {
        $config['layout'] = 'results';

        parent::__construct($config);
    }

    protected function getRendererResults()
    {
        $renderer = Factory::buildPageRenderer('viewable');
        $postZone = Factory::buildPostZoneResults();

        $renderer->setPostZone($postZone);

        return $renderer;
    }

    protected function getSettings()
    {
        return LoveFactoryApplication::getInstance()->getSettings();
    }
}
