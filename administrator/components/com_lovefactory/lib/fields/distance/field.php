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

class LoveFactoryFieldDistance extends LoveFactoryField
{
    protected $accessPageBlackList = array('registration', 'profile_edit', 'profile_fillin', 'radius_search');

    public function renderInputSearch()
    {
        $data = htmlentities($this->data, ENT_COMPAT, 'UTF-8');

        $html = array();

        $html[] = '<input type="text" id="' . $this->getHtmlId() . '" name="' . $this->getHtmlName() . '" value="' . $data . '" size="5" />';
        $html[] = $this->getUnitLabel();

        return implode("\n", $html);
    }

    public function renderInputView()
    {
        if (JFactory::getUser()->id == $this->userId || is_null($this->data)) {
            return $this->renderInputBlank();
        }

        return ceil($this->data) . ' ' . $this->getUnitLabel();
    }

    public function getQuerySearchCondition($query)
    {
        $googleMapsField = $this->getParam('google_maps_field', null);

        if (!$googleMapsField) {
            return false;
        }

        $data = intval($this->data);

        // Check if condition is set
        if (is_null($data) || '' == $data) {
            return false;
        }

        $data = $query->escape($data);

        // Check if user is guest
        $user = JFactory::getUser();
        if ($user->guest) {
            return false;
        }

        // Load user location
        $geo = $this->getGeoForUser($googleMapsField);

        // Check if user has defined own location
        if (!$geo) {
            return false;
        }

        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $unit = $settings->distances_unit ? 3956 : 6371;

        $output = '(CASE WHEN p.user_id IS NOT NULL THEN ' . $unit . ' * acos( cos( radians(p.field_' . $googleMapsField . '_lat) ) * cos( radians(' . $geo->lat . ') ) * cos( radians(p.field_' . $googleMapsField . '_lng) - radians(' . $geo->lng . ') ) + sin( radians(p.field_' . $googleMapsField . '_lat) ) * sin( radians(' . $geo->lat . ') ) ) ELSE NULL END) < ' . $data;

        return $output;
    }

    public function getQueryView($query)
    {
        static $loaded = false;

        if ($loaded) {
            return true;
        }

        $loaded = true;

        $googleMapsField = $this->getParam('google_maps_field', null);

        if (!$googleMapsField) {
            return false;
        }

        $geo = $this->getGeoForUser($googleMapsField);

        if (!$geo) {
            return false;
        }

        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $unit = $settings->distances_unit ? 3956 : 6371;

        $this->addQueryElement($query, 'select', '(CASE WHEN p.user_id IS NOT NULL THEN ' . $unit . ' * acos( cos( radians(p.field_' . $googleMapsField . '_lat) ) * cos( radians(' . $geo->lat . ') ) * cos( radians(p.field_' . $googleMapsField . '_lng) - radians(' . $geo->lng . ') ) + sin( radians(p.field_' . $googleMapsField . '_lat) ) * sin( radians(' . $geo->lat . ') ) ) ELSE NULL END) AS ' . $query->quoteName($this->getId()));
    }

    public function getId()
    {
        return 'distance';
    }

    public function isRenderable()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        if (!$settings->enable_gmaps) {
            return false;
        }

        return parent::isRenderable();
    }

    protected function getUnitLabel()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $unit = $settings->distances_unit ? 'mi' : 'km';

        return FactoryText::_('field_distance_unit_' . $unit);
    }

    protected function getGeoForUser($googleMapsField, $userId = null)
    {
        static $tables = array();

        if (is_null($userId)) {
            $userId = JFactory::getUser()->id;
        }

        if (!isset($tables[$userId])) {
            $table = JTable::getInstance('Profile', 'Table');
            $result = $table->load($userId);

            $googleMapsField = $this->getParam('google_maps_field', null);

            $tables[$userId] = $userId && $result && !is_null($table->{'field_' . $googleMapsField . '_lat'}) && !is_null($table->{'field_' . $googleMapsField . '_lng'}) ? (object)array('lat' => $table->{'field_' . $googleMapsField . '_lat'}, 'lng' => $table->{'field_' . $googleMapsField . '_lng'}) : false;
        }

        return $tables[$userId];
    }
}
