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

jimport('joomla.application.component.model');

class FrontendModelZone extends FactoryModel
{
    function getZones($type_id)
    {
        $page = $this->getTable('page');
        $zones = $page->getZones($type_id);

        $zones = explode('#', $zones);

        return $zones;
    }

    function parseZones($zones, $fields)
    {
        $result = array();

        if ($zones[0] == '') {
            return $result;
        }

        foreach ($zones as $zone) {
            $zone = explode('_', $zone);

            $row = $zone[0];
            $col = $zone[1];
            $field_id = $zone[2];

            if (!isset($result[$row])) {
                $result[$row] = array();
            }

            if (!isset($result[$row][$col])) {
                $result[$row][$col] = array();
            }

            $result[$row][$col][] = $fields[$field_id];
        }

        return $result;
    }
}
