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

class FrontendModelMembersMap extends FactoryModel
{
    public function getMap()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        $map = LoveFactoryGoogleMaps::getInstance($settings->gmaps_api_key);

        return $map;
    }

    public function getMembers()
    {
        $results = $this->getResults();
        $showGrouped = 'map' == LoveFactoryApplication::getInstance()->getSettings()->members_map_grouped_members_display;

        return LoveFactoryHelper::prepareMapMembers($results, $showGrouped);
    }

    public function getLocation()
    {
        $location = array();

        // Get user's location.
        $user = JFactory::getUser();
        $table = $this->getTable('Profile', 'Table');
        $id = $this->getGoogleMapsFieldId();

        if ($id && !$user->guest && $table->load($user->id)) {
            $location['lat'] = isset($table->{'field_' . $id . '_lat'}) ? $table->{'field_' . $id . '_lat'} : 0;
            $location['lng'] = isset($table->{'field_' . $id . '_lng'}) ? $table->{'field_' . $id . '_lng'} : 0;
            $location['zoom'] = isset($table->{'field_' . $id . '_zoom'}) ? $table->{'field_' . $id . '_zoom'} : 0;
        } else {
            $settings = LoveFactoryApplication::getInstance()->getSettings();

            $location['lat'] = $settings->gmaps_default_x;
            $location['lng'] = $settings->gmaps_default_y;
            $location['zoom'] = $settings->gmaps_default_z;
        }

        return $location;
    }

    public function getMarkerClusterer()
    {
        return LoveFactoryApplication::getInstance()->getSettings('members_map_group_users', false);
    }

    public function getShowProfileMouseEvent()
    {
        return LoveFactoryApplication::getInstance()->getSettings()->members_map_show_profile_event;
    }

    public function getMarkerClustererZoom()
    {
        return LoveFactoryApplication::getInstance()->getSettings('members_map_group_zoom', 8);
    }

    public function getProfileInfoRoute()
    {
        return FactoryRoute::view('profilemap&format=raw', false, -1);
    }

    public function getProfileLinkNewWindow()
    {
        return LoveFactoryApplication::getInstance()->getSettings()->members_map_profile_new_link;
    }

    public function getShowMembersGrouped()
    {
        return true;
    }

    public function getLinkPageGroupedMembers()
    {
        return FactoryRoute::view('groupedmembers');
    }

    protected function getGoogleMapsFieldId()
    {
        static $id = null;

        if (is_null($id)) {
            $settings = LoveFactoryApplication::getInstance()->getSettings();

            $id = $settings->members_map_gmap_field;
        }

        return $id;
    }

    protected function getResults()
    {
        $dbo = $this->getDbo();
        $user = JFactory::getUser();
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $id = $this->getGoogleMapsFieldId();

        if (!$id) {
            return false;
        }

        $query = $dbo->getQuery(true)
            ->select('p.user_id, p.field_' . $id . '_lat AS lat, p.field_' . $id . '_lng AS lng, p.display_name AS username')
            ->from('#__lovefactory_profiles p')
            ->where('p.field_' . $id . '_lat IS NOT NULL AND p.field_' . $id . '_lat <> ' . $dbo->q(0))
            ->where('p.field_' . $id . '_lng IS NOT NULL AND p.field_' . $id . '_lng <> ' . $dbo->q(0))
            ->group('p.user_id, p.user_id');

        // Filter by default user membership.
        if (!$settings->members_map_default_membership_show) {
            $query->leftJoin('#__lovefactory_memberships_sold ms ON ms.id = p.membership_sold_id')
                ->leftJoin('#__lovefactory_memberships m ON m.id = ms.membership_id')
                ->where('m.default <> 1');
        }

        // Filter by profile online status
        $query->leftJoin('#__lovefactory_friends f ON ((f.sender_id = p.user_id AND f.receiver_id = ' . $user->id . ') OR (f.receiver_id = p.user_id AND f.sender_id = ' . $user->id . '))')
            ->where('(p.online = 0 OR (p.online = 1 AND (CASE WHEN f.id IS NOT NULL AND f.pending = 0 THEN 1 ELSE 0 END) = 1))');

        $helper = new ThePhpFactory\LoveFactory\Helper\OppositeGender($settings);
        if ($helper->isOppositeGenderSearchEnabled($user)) {
            $helper->addOppositeGenderSearchCondition($query, $user);
        }

        $results = $dbo->setQuery($query)
            ->loadObjectList();

        return $results;
    }
}
