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

class FrontendModelRadiusSearch extends FactoryModel
{
    public function getMap()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        $map = LoveFactoryGoogleMaps::getInstance($settings->gmaps_api_key);

        return $map;
    }

    public function getPage($page = 'radius_search', $mode = 'search')
    {
        $page = LoveFactoryPage::getInstance($page, $mode);

        $settings = LoveFactoryApplication::getInstance()->getSettings();
        if ($settings->opposite_gender_search) {
            $page->removeGenderFields();
        }

        return $page;
    }

    public function getResults($request)
    {
        $results = $this->getMembers($request);
        $showGrouped = 'map' == LoveFactoryApplication::getInstance()->getSettings()->members_map_grouped_members_display;

        return LoveFactoryHelper::prepareMapMembers($results, $showGrouped);
    }

    public function getMarkerClusterer()
    {
        return LoveFactoryApplication::getInstance()->getSettings('search_radius_group_users', false);
    }

    public function getMarkerClustererZoom()
    {
        return LoveFactoryApplication::getInstance()->getSettings('search_radius_group_zoom', 8);
    }

    public function getLocation()
    {
        $model = JModelLegacy::getInstance('MembersMap', 'FrontendModel');

        return $model->getLocation();
    }

    public function getProfileInfoRoute()
    {
        return FactoryRoute::view('profilemap&format=raw', false, -1);
    }

    public function getProfileLinkNewWindow()
    {
        return LoveFactoryApplication::getInstance()->getSettings()->search_radius_profile_new_link;
    }

    public function getShowProfileMouseEvent()
    {
        return LoveFactoryApplication::getInstance()->getSettings()->members_map_show_profile_event;
    }

    protected function addSearchConditions($query, $request)
    {
        // Initialise variables.
        $user = JFactory::getUser();
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        foreach ($this->getPage()->getFields() as $field) {
            $field->bind($request);
            $field->addQuerySearchCondition($query);

            $this->setState('data.filtered.' . $field->getId(), $field->getFilteredData());
        }

        // Don't return own profile.
        $query->where('p.user_id <> ' . $query->quote($user->id));

        // Filter banned profiles.
        if ($settings->hide_banned_profiles) {
            $query->where('p.banned = ' . $query->quote(0));
        }

        // Filter blocked users.
        if ($settings->hide_ignored_profiles) {
            $query->leftJoin('#__lovefactory_blacklist b ON b.sender_id = ' . $query->quote($user->id) . ' AND b.receiver_id = p.user_id')
                ->where('b.id IS NULL');
        }

        // Filter default membership users.
        if (!$settings->search_default_membership_show) {
            $query->where('p.membership_sold_id <> 1');
        }

        $helper = new ThePhpFactory\LoveFactory\Helper\OppositeGender($settings);
        if ($helper->isOppositeGenderSearchEnabled($user)) {
            $helper->addOppositeGenderSearchCondition($query, $user);
        }

        return $query;
    }

    protected function getGoogleMapsFieldId()
    {
        static $id = null;

        if (is_null($id)) {
            $settings = LoveFactoryApplication::getInstance()->getSettings();

            $id = $settings->search_radius_gmap_field;
        }

        return $id;
    }

    protected function getMembers($request)
    {
        $user = JFactory::getUser();
        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true);
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        $id = $this->getGoogleMapsFieldId();

        if (!$id) {
            return array();
        }

        $query->select('p.user_id, p.field_' . $id . '_lat AS lat, p.field_' . $id . '_lng AS lng, p.display_name AS username')
            ->from('#__lovefactory_profiles p')
            ->where('p.field_' . $id . '_lat IS NOT NULL AND p.field_' . $id . '_lat <> ' . $dbo->q(0))
            ->where('p.field_' . $id . '_lng IS NOT NULL AND p.field_' . $id . '_lng <> ' . $dbo->q(0))
            ->group('p.user_id');

        // Filter by profile online status
        $query->leftJoin('#__lovefactory_friends f ON ((f.sender_id = p.user_id AND f.receiver_id = ' . $user->id . ') OR (f.receiver_id = p.user_id AND f.sender_id = ' . $user->id . '))')
            ->where('(p.online = 0 OR (p.online = 1 AND (CASE WHEN f.id IS NOT NULL AND f.pending = 0 THEN 1 ELSE 0 END) = 1))');

        // Filter by default user membership.
        if (!$settings->search_radius_default_membership_show) {
            $query->leftJoin('#__lovefactory_memberships_sold ms ON ms.id = p.membership_sold_id')
                ->leftJoin('#__lovefactory_memberships m ON m.id = ms.membership_id')
                ->where('m.default <> 1');
        }

        $this->addSearchConditions($query, $request);

        //echo $query->dump();

        $results = $dbo->setQuery($query)
            ->loadObjectList();

        return $results;
    }
}
