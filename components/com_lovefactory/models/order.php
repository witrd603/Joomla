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

class FrontendModelOrder extends FactoryModel
{
    // Tasks
    function store()
    {
        $id = JFactory::getApplication()->input->getInt('price');
        $method = JFactory::getApplication()->input->getInt('method');
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        $method = 0 == $method ? 4 : $method;

        // Check for price
        $price = $this->getTable('price');
        $price->load($id);

        if (count($price->_errors) || $price->published === 0) {
            JFactory::getApplication()->enqueueMessage(JText::_('ORDER_TASK_STORE_ERROR_PRICE_NOT_FOUND'), 'error');
            return false;
        }

        // Check for gateway
        $gateway = $this->getTable('gateway');
        $gateway->load($method);

        if (count($gateway->_errors)) {
            JFactory::getApplication()->enqueueMessage(JText::_('ORDER_TASK_STORE_ERROR_GATEWAY_NOT_FOUND'), 'error');
            return false;
        }

        require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'settings.php');
        $settings = new LovefactorySettings();

        // All it's ok, save the order
        $order = $this->getTable();

        $order->user_id = $user->id;
        $order->membership_id = $price->membership_id;
        $order->params = $this->convertMembershipToParams($price->membership_id);
        $order->starting_type = 0;
        $order->amount = $price->price;
        $order->currency = $settings->currency;
        $order->months = $price->months;
        $order->gateway = $gateway->title;

        $order->store();

        $this->order_id = $order->id;

        return true;
    }

    function create($title, $price_id)
    {
        $Itemid = JFactory::getApplication()->input->getInt('Itemid', 0);

        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');

        // Check for gateway
        $gateway = $this->getTable('gateway');
        $gateway->findOneByTitle($title);

        if (count($gateway->_errors)) {
            JFactory::getApplication()->enqueueMessage(JText::_('ORDER_TASK_CREATE_ERROR_GATEWAY_NOT_FOUND'), 'error');
            $mainframe->redirect(JRoute::_('index.php?option=com_lovefactory&view=membershipbuy&Itemid=' . $Itemid, false));
        }

        // Check for price
        $price = $this->getTable('price');
        $price->load($price_id);

        if (count($price->_errors) || $price->published === 0) {
            JFactory::getApplication()->enqueueMessage(JText::_('ORDER_TASK_CREATE_ERROR_PRICE_NOT_FOUND'), 'error');
            return false;
        }

        $settings = new LovefactorySettings();
        $user = JFactory::getUser();
        $profile = $this->getTable('profile');

        $profile->load($user->id);

        // Get gender price
        if ($settings->gender_pricing) {
            $price->price = $price->getGenderPrice($profile->sex);
        }

        // All it's ok, create a new order
        $order = $this->getTable();

        $order->user_id = $user->id;
        $order->membership_id = $price->membership_id;
        $order->params = $this->convertMembershipToParams($price->membership_id);
        $order->starting_type = 0;
        $order->amount = $price->price;
        $order->currency = $settings->currency;
        $order->months = $price->months;
        $order->gateway = $gateway->title;

        $order->store();

        return $order;
    }

    // Helpers
    function convertMembershipToParams($membership_id)
    {
        $membership = $this->getTable('membership');
        $membership->load($membership_id);

        $params = array();

        foreach ($membership as $key => $value) {
            if (substr($key, 0, 1) != '_') {
                $params[] = $key . '=' . $value;
            }
        }

        return implode("\n", $params);
    }
}
