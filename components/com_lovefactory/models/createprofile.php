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

JLoader::register('FrontendModelSignup', JPATH_SITE . '/components/com_lovefactory/models/signup.php');

class FrontendModelCreateProfile extends FrontendModelSignup
{
    protected $context = 'com_lovefactory.profile.create.data';

    public function create(array $data = array())
    {
        $originalData = $data;

        $page = $this->getPage('registration', 'edit', false);

        $page->bind($data);
        $valid = $page->validate();

        if (!$valid) {
            if (!$this->renderErrorsIndividual) {
                $this->setError(implode('<br />', $page->getErrors()));
            } else {
                $this->setError(FactoryText::plural('page_saving_error', count($page->getErrors())));
            }

            return false;
        }

        $data = $this->prepareData();

        // Joomla Registration
        $language = JFactory::getLanguage();
        $language->load('com_users');

        $user = new JUser;
        $params = JComponentHelper::getParams('com_users');

        $data['groups'] = array($params->get('new_usertype', 2));
        $data['block'] = 0;

        // Bind the data.
        if (!$user->bind($data)) {
            $this->setError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));
            return false;
        }

        // Load the users plugin group.
        JPluginHelper::importPlugin('user');

        // Store the data.
        if (!$user->save()) {
            $this->setError(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $user->getError()));
            return false;
        }

        // Check if membership is set.
        $membershipSoldId = 0;

        if (isset($originalData['gateway'])) {
            $membershipSoldId = $this->createNewMembership($originalData['gateway'], $user->id);
        }

        // Save Love Factory profile.
        if (!$this->saveLoveFactoryProfile($page, $user, $membershipSoldId)) {
            return false;
        }

        return true;
    }

    protected function createNewMembership($priceId, $userId)
    {
        $price = JTable::getInstance('Price', 'Table');
        $price->load($priceId);

        if ($price->months) {
            $expiration = strtotime('+ ' . $price->months . ' months');
        } else {
            $expiration = 0;
        }

        $membership = JTable::getInstance('Membership', 'Table');
        $membership->load($price->membership_id);

        $membershipUser = JTable::getInstance('MembershipSold', 'Table');
        $membershipUser->createFromMembership($membership, $expiration, $userId);

        $membershipUser->store();

        return $membershipUser->id;
    }
}
