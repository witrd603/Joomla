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

class FrontendModelGateway extends FactoryModel
{
    // Task
    public function process()
    {
        // Initialize variables
        if (JFactory::getUser()->guest) {
            $sessionId = JFactory::getSession()->get('registration_membership_user_id');
            $userId = JFactory::getApplication()->input->get('user_id', null);

            if (intval($userId) !== intval($sessionId)) {
                $userId = null;
            }
        } else {
            $userId = JFactory::getUser()->id;
        }

        $user = JFactory::getUser($userId);
        $dbo = JFactory::getDbo();
        $settings = new LoveFactorySettings();
        $step = JFactory::getApplication()->input->getInt('step', 0);
        $gateway_id = JFactory::getApplication()->input->getCmd('method', '');
        $price_id = JFactory::getApplication()->input->getInt('price', 0);
        $profile = $this->getTable('Profile', 'Table');
        $price = $this->getTable('Price', 'Table');
        $membership = $this->getTable('Membership', 'Table');
        $gateway = $this->getTable('Gateway', 'Table');
        $order = $this->getTable('Order', 'Table');
        $mailer = JModelLegacy::getInstance('Mailer', 'FrontendModel');

        // Check if price is available
        if (!$user->id) {
            $this->setError(FactoryText::_('gateway_process_error_user_not_found'));
            return false;
        }

        // Load price
        if ((!empty($price_id) && !$price->load($price_id)) || empty($price_id)) {
            $this->setError(FactoryText::_('gateway_process_error_price_not_found'));
            return false;
        }

        // Check if price is available
        if (!$price->published) {
            $this->setError(FactoryText::_('gateway_process_error_price_not_found'));
            return false;
        }

        // Check if gender price is available
        $profile->load($user->id);
        if ($settings->gender_pricing && !$price->hasGender($profile->sex)) {
            $this->setError(FactoryText::_('gateway_process_error_price_not_available'));
            return false;
        }

        // Load membership
        if ((!empty($price->membership_id) && !$membership->load($price->membership_id)) || empty($price->membership_id)) {
            $this->setError(FactoryText::_('gateway_process_error_membership_not_found'));
            return false;
        }

        // Check if membership is available
        if (!$membership->published) {
            $this->setError(FactoryText::_('gateway_process_error_membership_not_found'));
            return false;
        }

        // Find gateway
        $result = $gateway->find(array('id' => $gateway_id));
        if (!$result) {
            $this->setError(FactoryText::_('gateway_process_error_not_found'));
            return false;
        }

        $gateway->load($result);

        // Check if gateway is enabled
        if (!$gateway->published) {
            $this->setError(FactoryText::_('gateway_process_error_not_found'));
            return false;
        }

        // All is ok
        JLoader::register($gateway->element, JPATH_COMPONENT_ADMINISTRATOR . DS . 'payment' . DS . 'gateways' . DS . $gateway->element . DS . $gateway->element . '.php');

        // Load gateway language file
        $language = JFactory::getLanguage();
        $language->load($gateway->element, JPATH_COMPONENT_ADMINISTRATOR . DS . 'payment');

        // Create new gateway object
        $gateway = new $gateway->element(array(
            'gateway' => $gateway,
            'price' => $price,
            'membership' => $membership,
            'orderTable' => $order,
            'dbo' => $dbo,
            'settings' => $settings,
            'profile' => $profile,
            'mailer' => $mailer,
            'price_id' => $price_id,
        ));

        // Execute current step
        if (!$gateway->executeStep($step)) {
            $this->setError($gateway->getError());

            return false;
        }

        return true;
    }
}
