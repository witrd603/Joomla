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

class FrontendViewRadiusSearch extends FactoryView
{
    protected
        $get = array(
        'map',
        'renderer',
        'page',
        'location',
    ),
        $js = array('markerclusterer/src/markerclusterer', 'googlemapsmarker', 'views/radiussearch'),
        $css = array('views/membersmap', 'views/radiussearch'),
        $javascriptVariables = array(
        'markerClusterer',
        'markerClustererZoom',
        'location',
        'profileInfoRoute',
        'profileLinkNewWindow',
        'showProfileMouseEvent'
    ),
        $behaviors = array('factoryjQueryUi');

    public function display($tpl = null)
    {
        $key = LoveFactoryApplication::getInstance()->getSettings('gmaps_api_key', '');
        if ($key) {
            JHtml::script('https://maps.googleapis.com/maps/api/js?key=' . $key);
        }

        $path = JUri::root() . 'components/com_lovefactory/assets/js/markerclusterer/images/m';
        JFactory::getDocument()->addScriptDeclaration(
            'MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_PATH_ = "' . htmlentities($path) . '";'
        );

        return parent::display($tpl);
    }

    protected function getRenderer()
    {
        $renderer = Factory::buildPageRenderer('searchable');

        return $renderer;
    }
}
