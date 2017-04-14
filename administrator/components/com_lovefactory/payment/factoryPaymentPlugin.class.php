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

jimport('joomla.html.parameter');

abstract class factoryPaymentPlugin extends JObject
{
    var $title;
    var $gateway;
    var $price;
    var $membership;
    var $orderTable;
    var $dbo;
    var $settings;
    var $profile;
    var $mailer;
    protected $_errors = array();

    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->set('element', strtolower(get_class($this)));

        $root = JURI::root();
        $root = str_replace('/components/com_lovefactory', '', $root);

        $this->set('url.return', JRoute::_($root . 'index.php?option=com_lovefactory&view=payment&layout=complete', false, -1));
        $this->set('url.cancel', JRoute::_($root . 'index.php?option=com_lovefactory&view=payment&layout=cancel', false, -1));
        $this->set('url.complete', JRoute::_($root . 'index.php?option=com_lovefactory&view=payment&layout=complete', false, -1));
        $this->set('url.failed', JRoute::_($root . 'index.php?option=com_lovefactory&view=payment&layout=failed', false, -1));
        $this->set('url.notification', $root . 'components/com_lovefactory/payment.php?gateway=' . $this->getId());
    }

    public function executeStep($step = 1)
    {
        if (!method_exists($this, 'step' . $step)) {
            $this->setError(JText::sprintf('Gateway "%s" does not have step "%s"!', $this->get('title'), $step));
            return false;
        }

        return $this->{'step' . $step}();
    }

    public function getLogo()
    {
        $gateway = $this->get('gateway');

        if (isset($gateway->logo) && $gateway->logo) {
            return $gateway->logo;
        }

        jimport('joomla.filesystem.folder');

        $logos = JFolder::files(JPATH_ADMINISTRATOR . '/components/com_lovefactory/payment/gateways/' . $this->get('element'), '^' . $this->get('element') . '.(jpg|png|gif)');

        if (count($logos)) {
            $logo = $logos[0];

            return JURI::root() . 'administrator/components/com_lovefactory/payment/gateways/' . $this->get('element') . '/' . $logo;
        }

        return '';
    }

    public function getTitle()
    {
        return $this->get('gateway')->title ? $this->get('gateway')->title : $this->element;
    }

    public function getId()
    {
        return $this->get('gateway')->id;
    }

    protected function createOrder()
    {
        $order = $this->get('orderTable')->createFrom(array(
            'price' => $this->get('price'),
            'membership' => $this->get('membership'),
            'settings' => $this->get('settings'),
            'profile' => $this->get('profile'),
            'gateway' => $this->getId(),
        ));

        if (!$order) {
            $this->setError($this->get('orderTable')->getError());
            return false;
        }

        $this->set('order', $order);

        return true;
    }

    protected function getParam($param, $default = null)
    {
        return $this->get('gateway')->params->get($param, $default);
    }

    protected function createPayment($ipn)
    {
        $payment = JTable::getInstance('Payment', 'Table');

        $payment->data = $ipn->toString();
        $payment->gateway = $this->getId();
        $payment->payment_date = $ipn->get('payment_date');
        $payment->user_id = $ipn->get('user_id');
        $payment->amount = $ipn->get('amount');
        $payment->currency = $ipn->get('currency');
        $payment->order_id = $ipn->get('order_id');
        $payment->refnumber = $ipn->get('refnumber');

        return $payment;
    }

    protected function validateUser($id)
    {
        $user = JFactory::getUser($id);

        if (!$user->id) {
            return false;
        }

        return true;
    }

    protected function findOrder($order_id)
    {
        static $orders = array();

        if (!isset($orders[$order_id])) {
            $order = JTable::getInstance('Order', 'Table');

            if (is_null($order_id) || !$order_id || !$order->load($order_id)) {
                $orders[$order_id] = false;
            } else {
                $orders[$order_id] = $order;
            }
        }

        return $orders[$order_id];
    }

    protected function savePayment($payment, $errors = array())
    {
        $payment->errors = implode("\n", $errors);

        $payment->store();

        // Trigger new payment received event.
        JEventDispatcher::getInstance()->trigger('onLoveFactoryPaymentReceived', array(
            'com_lovefactory.payment.received',
            $payment,
        ));
    }

    protected function getButton()
    {
        if ($this->getParam('button_src')) {
            return $this->getParam('button_src');
        }

        return $this->getLogo();
    }

    public function getError($i = null, $toString = true)
    {
        // Find the error
        if ($i === null) {
            // Default, return the last message
            $error = end($this->_errors);
        } elseif (!array_key_exists($i, $this->_errors)) {
            // If $i has been specified but does not exist, return false
            return false;
        } else {
            $error = $this->_errors[$i];
        }

        // Check if only the string is requested
        if ($error instanceof Exception && $toString) {
            return (string)$error;
        }

        return $error;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function setError($error)
    {
        array_push($this->_errors, $error);
    }
}
