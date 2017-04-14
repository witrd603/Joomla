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

class LoveFactoryFieldRadius extends LoveFactoryField
{
    protected $accessPageWhiteList = array('radius_search');

    public function renderInputEdit()
    {
        if (null === $this->data) {
            $this->data = $this->getParams()->get('default');
        }

        $data = htmlentities($this->data, ENT_COMPAT, 'UTF-8');
        $html = array();

        $html[] = '<input type="text" id="' . $this->getHtmlId() . '_distance" name="' . $this->getHtmlName() . '[distance]" value="' . $data . '" size="4" />';
        $html[] = '<input type="hidden" id="' . $this->getHtmlId() . '_lat" name="' . $this->getHtmlName() . '[lat]" value="' . $data . '" />';
        $html[] = '<input type="hidden" id="' . $this->getHtmlId() . '_lng" name="' . $this->getHtmlName() . '[lng]" value="' . $data . '" />';

        $html[] = $this->getUnitLabel();

        return implode("\n", $html);
    }

    public function getId()
    {
        return 'radius';
    }

    public function getQuerySearchCondition($query)
    {
        $googleMapsField = $this->getParam('google_maps_field', null);

        if (!$googleMapsField) {
            return false;
        }

        $this->filterData();

        $distance = $this->data['distance'];

        // Check if condition is set
        if (is_null($distance) || !$distance) {
            return false;
        }

        $distance = $query->escape($distance);

        $geo = (object)array('lat' => $this->data['lat'], 'lng' => $this->data['lng']);

        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $unit = $settings->distances_unit ? 3956 : 6371;

        $output = '(CASE WHEN p.user_id IS NOT NULL THEN ' . $unit . ' * acos( cos( radians(p.field_' . $googleMapsField . '_lat) ) * cos( radians(' . $geo->lat . ') ) * cos( radians(p.field_' . $googleMapsField . '_lng) - radians(' . $geo->lng . ') ) + sin( radians(p.field_' . $googleMapsField . '_lat) ) * sin( radians(' . $geo->lat . ') ) ) ELSE NULL END) < ' . $distance;

        return $output;
    }

    public function filterData()
    {
        $distance = $this->data['distance'];
        $maxDistance = intval($this->getParam('max_radius', ''));

        if ($maxDistance && $distance > $maxDistance) {
            $this->data['distance'] = $maxDistance;
        }
    }

    protected function getUnitLabel()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $unit = $settings->distances_unit ? 'mi' : 'km';

        return FactoryText::_('field_distance_unit_' . $unit);
    }
}
