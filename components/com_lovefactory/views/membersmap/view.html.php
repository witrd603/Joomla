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

class FrontendViewMembersMap extends FactoryView
{
    protected
        $get = array('map', 'location'),
        $js = array('markerclusterer/src/markerclusterer', 'googlemapsmarker'),
        $javascriptVariables = array(
        'markerClusterer',
        'markerClustererZoom',
        'members',
        'profileInfoRoute',
        'profileLinkNewWindow',
        'showProfileMouseEvent',
        'showMembersGrouped',
        'linkPageGroupedMembers',
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
}
