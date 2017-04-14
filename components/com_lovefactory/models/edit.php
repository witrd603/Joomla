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

class FrontendModelEdit extends FactoryModel
{
    public function getProfile()
    {
        static $profiles = array();

        // Initialise variables.
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $userId = JFactory::getUser()->id;

        if (!isset($profiles[$userId])) {
            // Get, if exists, the draft profile.
            $draft = $this->getDraftProfile($userId, $settings->approval_profile);

            // Check if draft profile exists.
            if (false !== $draft) {
                $profiles[$userId] = $draft->profile->toObject();

                $profiles[$userId]->isDraft = true;
                $profiles[$userId]->isPending = $draft->pending;
            } else {
                // Draft profile does not exist, get current profile.
                $profile = $this->getCurrentProfile($userId);

                $profiles[$userId] = $profile;

                $profiles[$userId]->isDraft = false;
                $profiles[$userId]->isPending = false;
            }
        }

        return $profiles[$userId];
    }

    public function update($data, $userId = null)
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if (is_null($userId)) {
            $userId = JFactory::getUser()->id;
        }

        // Initialise variables
        $profile = $this->getTable('Profile', 'Table');
        $profile->load($userId);

        // Store geo location address.
        $oldAddress = $this->getLocationAddress($profile);

        // Update profile fields and fields visibilities
        $page = $this->getPage('profile_edit', 'edit', false);
        $page->bind($data);
        $page->bindOriginalProfile($profile);

        $valid = $page->validate();

        if (!$valid) {
            $this->setState('filtered.data', $page->getFilteredData());
            $this->setError(implode('<br />', $page->getErrors()));
            return false;
        }

        if ($settings->approval_profile && !JFactory::getApplication()->isAdmin()) {
            $draft = $this->getTable('ProfileUpdate');
            $draft->createFrom($userId, $page->convertDataToProfile());

            $params = new JRegistry($draft->profile);
            foreach ($params->toArray() as $key => $value) {
                $draft->$key = $value;
            }

            $this->updateLocation($draft, $oldAddress);
        } else {
            $profile->bindFromRequest($page->convertDataToProfile());

            $dispatcher = JEventDispatcher::getInstance();
            $dispatcher->trigger('onLoveFactoryProfileBeforeSave', array(
                'com_lovefactory.profile.save.before',
                $profile
            ));

            // Save the profile.
            if (!$profile->store(true)) {
                $this->setError($profile->getError());
                return false;
            }

            $page->postProfileSave($profile);

            // Update Google Maps Location based on Location fields.
            $this->updateLocation($profile, $oldAddress);
        }

        return true;
    }

    public function submitForApproval($user_id)
    {
        $table = JTable::getInstance('ProfileUpdate', 'Table');
        $table->loadLatest($user_id);

        if (!$table->id) {
            $this->setError(FactoryText::_('profile_task_restore_error_profile_not_found'));
            return false;
        }

        if (!$table->submitForApproval()) {
            return false;
        }

        // Send pending approval notifications.
        $mailer = FactoryMailer::getInstance();
        $mailer->sendAdminNotification(
            'item_pending_approval',
            array(
                'item_type' => 'profile',
            ));

        return true;
    }

    public function restore($user_id)
    {
        $table = JTable::getInstance('ProfileUpdate', 'Table');
        $table->loadLatest($user_id);

        if (!$table->id) {
            $this->setError(FactoryText::_('profile_task_restore_error_profile_not_found'));
            return false;
        }

        return $table->delete();
    }

    protected function getDraftProfile($userId, $profileApproval)
    {
        if (!$profileApproval) {
            return false;
        }

        $table = $this->getTable('ProfileUpdate');

        if (!$table->loadLatestProfile($userId)) {
            return false;
        }

        return $table;
    }

    public function getPage($title = 'profile_edit', $mode = 'edit', $loadData = true)
    {
        /* @var $page LoveFactoryPage */
        $page = LoveFactoryPage::getInstance($title, $mode, array(
            'renderErrorsIndividual' => true,
            'isAdmin' => JFactory::getApplication()->isAdmin(),
        ));

        if ($loadData) {
            $session = JFactory::getSession();
            $context = 'com_lovefactory.profile.edit.data';

            $profile = $this->getProfile();
            $data = is_null($session->get($context, null)) ? $profile : $session->get($context, null);

            if (!is_null($data)) {
                $page->bind($data);
            }

            $page->bindOriginalProfile($this->getCurrentProfile(JFactory::getUser()->id));

            $session->set($context, null);
        }

        return $page;
    }

    protected function getCurrentProfile($userId)
    {
        $user = JFactory::getUser();
        $dbo = $this->getDbo();

        $query = $dbo->getQuery(true)
            ->select('p.*')
            ->from('#__lovefactory_profiles p')
            ->where('p.user_id = ' . $dbo->quote($userId));

        // Select the username.
        $query->select('u.username')
            ->leftJoin('#__users u ON u.id = p.user_id');

        // Select if users are friends.
        $query->select('f.id AS is_friend')
            ->leftJoin('#__lovefactory_friends f ON ((f.sender_id = p.user_id AND f.receiver_id = ' . $dbo->quote($user->id) . ') OR (f.receiver_id = p.user_id AND f.sender_id = ' . $dbo->quote($user->id) . ')) AND f.pending = ' . $dbo->quote(0));

        // Select if user is blocked.
        $query->select('b.id AS blocked')
            ->leftJoin('#__lovefactory_blacklist b ON b.sender_id = ' . $dbo->quote($user->id) . ' AND b.receiver_id = p.user_id');

        foreach ($this->getPage('profile_edit', 'edit', false)->getFields() as $field) {
            $field->addQueryView($query);
        }

        $result = $dbo->setQuery($query)
            ->loadObject();

        $table = JTable::getInstance('Profile', 'Table');
        $table->bind($result);

        return $table;
    }

    public function updateLocation($profile, $oldAddress = '')
    {
        // Initialise variables.
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        // Check if Google Maps integration and location fields are enabled.
        if (!$settings->enable_gmaps || !$settings->fields_location) {
            return false;
        }

        // Get current user address.
        $address = $this->getLocationAddress($profile);

        // Check if update location only when it has changed.
        if ($settings->update_fields_location == 1 && $address === $oldAddress) {
            return false;
        }

        // Check if location is defined.
        if ('' == $address['city'] && '' == $address['country']) {
            return false;
        }

        // Get Geo Location from Google Maps.
        $googleMaps = LoveFactoryGoogleMaps::getInstance($settings->gmaps_api_key);
        $result = $googleMaps->geoCode(implode(',', $address));

        // Check if response is valid.
        if (!$result) {
            return false;
        }

        $fieldId = $settings->location_field_gmap_field;
        $data = array(
            'field_' . $fieldId . '_lat' => $result->lat,
            'field_' . $fieldId . '_lng' => $result->lng,
            'field_' . $fieldId . '_zoom' => 8,
        );

        // Check if updating a draft profile.
        if ($profile instanceof TableProfileUpdate) {
            $params = new JRegistry($profile->profile);
            foreach ($params->toArray() as $key => $value) {
                unset($profile->$key);
            }

            $params->loadArray($data);
            $profile->profile = $params->toString();
        }

        if (!$profile->save($data)) {
            return false;
        }

        return true;
    }

    protected function getLocationAddress($profile)
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if (!$settings->fields_location) {
            return false;
        }

        $location = array('city' => '', 'state' => '', 'country' => '');

        // Get city.
        if ($settings->location_field_city) {
            $table = $this->getTable('Field');
            $table->load($settings->location_field_city);

            $field = LoveFactoryField::getInstance($table->type, $table);
            $field->bind($profile);

            $location['city'] = $field->getData();
        }

        // Get state.
        if ($settings->location_field_state) {
            $table = $this->getTable('Field');
            $table->load($settings->location_field_state);

            $field = LoveFactoryField::getInstance($table->type, $table);
            $field->bind($profile);

            $location['state'] = $field->getData();
        }

        // Get country.
        if ($settings->location_field_country) {
            $table = $this->getTable('Field');
            $table->load($settings->location_field_country);

            $field = LoveFactoryField::getInstance($table->type, $table);
            $field->bind($profile);

            $location['country'] = $field->getData();
        }

        return $location;
    }
}
