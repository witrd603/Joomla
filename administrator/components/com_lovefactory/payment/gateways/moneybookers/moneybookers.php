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

require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'payment' . DS . 'factoryPaymentPlugin.class.php');

class Moneybookers extends factoryPaymentPlugin
{
    public function step1()
    {
        // Create a new order
        if (!$this->createOrder()) {
            return false;
        }

        // Show the confirmation form
        ?>

        <h1><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_MONEYBOOKERS_CONFIRM_PAGE_TITLE'); ?></h1>
        <p><?php echo JText::sprintf('FACTORY_PAYMENT_PLUGIN_MONEYBOOKERS_CONFIRM_PAGE_TEXT', $this->get('order')->title); ?></p>

        <br/>

        <form action="<?php echo $this->getParam('action'); ?>" method="post">
            <input type="hidden" name="pay_to_email" value="<?php echo $this->getParam('email'); ?>"/>
            <input type="hidden" name="hide_login" value="0"/>
            <input type="hidden" name="merchant_fields" value="m_userid,m_itemnr"/>
            <input type="hidden" name="m_userid" value="<?php echo $this->get('order')->user_id; ?>"/>
            <input type="hidden" name="m_itemnr" value="<?php echo $this->get('order')->id; ?>"/>
            <input type="hidden" name="language" value="EN"/>

            <input type="hidden" name="return_url" value="<?php echo $this->get('url.complete'); ?>"/>
            <input type="hidden" name="cancel_url" value="<?php echo $this->get('url.cancel'); ?>"/>
            <input type="hidden" name="status_url" value="<?php echo $this->get('url.notification'); ?>"/>

            <input type="hidden" name="amount" value="<?php echo $this->get('order')->amount; ?>"/>
            <input type="hidden" name="currency" value="<?php echo $this->get('order')->currency; ?>"/>
            <input type="hidden" name="detail1_description"
                   value="<?php echo JText::_('FACTORY_PAYMENT_PLUGIN_MONEYBOOKERS_ITEM_DESC'); ?>"/>
            <input type="hidden" name="detail1_text" value="<?php echo $this->get('order')->title; ?>"/>

            <input type="image" src="<?php echo $this->getButton(); ?>" name="submit"/>

        </form>

        <?php

        return true;
    }

    public function processIpn()
    {
        $ipn = new JRegistry(JFilterInput::getInstance()->clean($_POST, null));

        // Preprocess ipn
        $ipn->set('user_id', $ipn->get('m_userid'));
        $ipn->set('amount', $ipn->get('mb_amount'));
        $ipn->set('currency', $ipn->get('mb_currency'));
        $ipn->set('order_id', $ipn->get('m_itemnr'));
        $ipn->set('refnumber', $ipn->get('mb_transaction_id'));

        // Create payment
        $payment = $this->createPayment($ipn);

        // Check for errors
        $errors = $this->validatePayment($ipn);

        switch ($this->status) {
            case '2':
                $payment->status = $errors ? 40 : 20; // 40 - Manual check; 20 - Completed
                break;

            case '-1':
            case '-2':
            case '-3':
                $payment->status = 30; // 30 - Failed
                break;

            case '0':
            default:
                $payment->status = 10; // 10 - Pending
                break;
        }

        $this->savePayment($payment, $errors);
    }

    protected function validatePayment($ipn)
    {
        $errors = array();

        // Validate paypal email
        if ($ipn->get('pay_to_email') != $this->getParam('email')) {
            $errors[] = JText::_('FACTORY_PAYMENT_PLUGIN_MONEYBOOKERS_VALIDATION_FAILED_RECEIVER_EMAIL_IS_DIFFERENT');
        }

        // Validate Order
        $order = $this->findOrder($ipn->get('order_id'));

        if ($order) {
            // Validate amount
            if ($order->amount != $ipn->get('amount')) {
                $errors[] = JText::_('FACTORY_PAYMENT_PLUGIN_MONEYBOOKERS_VALIDATION_FAILED_AMOUNT_IS_DIFFERENT');
            }

            // Validate currency
            if ($order->currency != $ipn->get('currency')) {
                $errors[] = JText::_('FACTORY_PAYMENT_PLUGIN_MONEYBOOKERS_VALIDATION_FAILED_CURRENCY_IS_DIFFERENT');
            }
        } else {
            $errors[] = JText::_('FACTORY_PAYMENT_PLUGIN_MONEYBOOKERS_VALIDATION_FAILED_ORDER_NOT_FOUND');
        }

        return $errors;
    }
}
