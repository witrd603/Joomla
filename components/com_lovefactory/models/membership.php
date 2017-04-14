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

class FrontendModelMembership extends FactoryModel
{
    public function trial()
    {
        $id = JFactory::getApplication()->input->getInt('id');
        $user = JFactory::getUser();

        // Check if user is logged in
        if ($user->guest) {
            JFactory::getApplication()->enqueueMessage(FactoryText::_('membership_trial_error_not_logged_in'), 'error');
            JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_users&view=login'));
            return false;
        }

        // Check if user has a default membership
        /** @var TableProfile $profile */
        $profile = $this->getTable('Profile', 'Table');
        $profile->load($user->id);

        if (!$profile->hasDefaultMembership()) {
            JFactory::getApplication()->enqueueMessage(FactoryText::_('membership_trial_error_only_default_membership'), 'error');
            JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_lovefactory&view=memberships'));
            return false;
        }

        // Check if required membership is trial
        $date = JFactory::getDate();
        /** @var TablePrice $price */
        $price = $this->getTable('price', 'Table');
        $price->load($id);

        if (1 != $price->is_trial ||
            !$price->published ||
            ($price->available_interval && (strtotime($price->available_from) > $date->toUnix() || strtotime($price->available_until) < $date->toUnix()))
        ) {
            JFactory::getApplication()->enqueueMessage(FactoryText::_('membership_trial_error_not_found'), 'error');
            return false;
        }

        // Check if user hasn't used a trial membership before
        if (!$price->new_trial && 0 < $profile->trials) {
            JFactory::getApplication()->enqueueMessage(FactoryText::_('membership_trial_error_already_used_trial'), 'error');
            return false;
        }

        // Check if user has used this trial before
        $result = $this->userHasUsedTrial($user->id, $id);
        if ($result) {
            JFactory::getApplication()->enqueueMessage(FactoryText::_('membership_trial_error_trial_used_before'), 'error');
            return false;
        }

        // Get membership.
        /** @var TableMembership $membership */
        $membership = $this->getTable('Membership');
        $membership->load($price->membership_id);

        // Calculate expiration date.
        $expiration = $price->calculateExpirationDate();

        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_lovefactory/models');
        /** @var BackendModelUserMembership $model */
        $model = JModelLegacy::getInstance('UserMembership', 'BackendModel');

        return $model->trialUpdate($profile, $membership, $expiration);
    }

    public function free()
    {
        $id = JFactory::getApplication()->input->getInt('id');
        $user = JFactory::getUser();
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        // Check if user is logged in
        if ($user->guest) {
            JFactory::getApplication()->enqueueMessage(FactoryText::_('membership_free_error_not_logged_in'), 'error');
            JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_users&view=login'));
            return false;
        }

        // Check if price exists
        /** @var TablePrice $price */
        $price = $this->getTable('Price');
        if (!$id || !$price->load($id)) {
            JFactory::getApplication()->enqueueMessage(FactoryText::_('membership_free_error_not_found'), 'error');
            return false;
        }

        // Check if price is available for user's gender
        /** @var TableProfile $profile */
        $profile = $this->getTable('Profile');
        $profile->load($user->id);

        if ($settings->gender_pricing && (!$price->hasGender($profile->sex) || '0.00' != $price->getGenderPrice($profile->sex))) {
            JFactory::getApplication()->enqueueMessage(FactoryText::_('meembership_free_error_gender_not_available'), 'error');
            return false;
        }

        // Get membership.
        /** @var TableMembership $membership */
        $membership = $this->getTable('Membership');
        $membership->load($price->membership_id);

        // Calculate expiration date.
        $expiration = $price->calculateExpirationDate();

        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_lovefactory/models');
        /** @var BackendModelUserMembership $model */
        $model = JModelLegacy::getInstance('UserMembership', 'BackendModel');

        return $model->freeUpdate($profile, $membership, $expiration);
    }

    protected function userHasUsedTrial($userId, $id)
    {
        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('m.id')
            ->from('#__lovefactory_memberships_sold m')
            ->where('m.user_id = ' . $dbo->quote($userId))
            ->where('m.trial = ' . $dbo->quote($id));

        $result = $dbo->setQuery($query)
            ->loadResult();

        return $result;
    }
}
