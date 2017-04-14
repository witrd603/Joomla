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

class FrontendControllerMembersMap extends FrontendController
{
    function getMoreInfo()
    {
        $userId = JFactory::getApplication()->input->getInt('user_id');
        JFactory::getApplication()->input->set('id', $userId);

        $renderer = JModelLegacy::getInstance('renderer', 'FrontendModel');
        $membersmap = JModelLegacy::getInstance('membersmap', 'FrontendModel');

        $lat = JFactory::getApplication()->input->getFloat('lat', 0);
        $lng = JFactory::getApplication()->input->getFloat('lng', 0);

        $overrides = (0 != $lat && 0 != $lng) ? array('lat' => $lat, 'lng' => $lng) : array();

        echo $renderer->renderPageZones($this->getZones(), $membersmap->getProfile($overrides), 'view', 'moreinfo');

        jexit();
    }

    function getZones()
    {
        static $zones = null;

        if (!is_null($zones)) {
            return $zones;
        }

        $model = JModelLegacy::getInstance('zones', 'FrontendModel');
        $zones = $model->getZonesForDisplayForPage('moreinfo');

        return $zones;
    }
}
