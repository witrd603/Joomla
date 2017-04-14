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

class Paypal extends factoryPaymentPlugin
{
    public function step1()
    {
        // Create a new order
        if (!$this->createOrder()) {
            return false;
        }

        // Show the confirmation form
        ?>

        <h1><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_PAYPAL_CONFIRM_PAGE_TITLE'); ?></h1>
        <p><?php echo JText::sprintf('FACTORY_PAYMENT_PLUGIN_PAYPAL_CONFIRM_PAGE_TEXT', $this->get('order')->title); ?></p>

        <br/>

        <form action="<?php echo $this->getAction(); ?>" method="post">
            <input type="hidden" name="item_number" value="<?php echo $this->get('order')->id; ?>"/>
            <input type="hidden" name="on0" value="userid"/>
            <input type="hidden" name="os0" value="<?php echo $this->get('order')->user_id; ?>"/>
            <input type="hidden" name="amount" value="<?php echo $this->get('order')->amount; ?>"/>
            <input type="hidden" name="currency_code" value="<?php echo $this->get('order')->currency; ?>"/>

            <input type="hidden" name="cmd" value="_xclick"/>
            <input type="hidden" name="business" value="<?php echo $this->getParam('email'); ?>"/>
            <input type="hidden" name="item_name" value="<?php echo $this->get('order')->title; ?>"/>
            <input type="hidden" name="quantity" value="1"/>

            <input type="hidden" name="return" value="<?php echo $this->get('url.complete'); ?>"/>
            <input type="hidden" name="cancel_return" value="<?php echo $this->get('url.cancel'); ?>"/>
            <input type="hidden" name="notify_url" value="<?php echo $this->get('url.notification'); ?>"/>

            <input type="hidden" name="tax" value="0"/>
            <input type="hidden" name="no_note" value="1"/>
            <input type="hidden" name="no_shipping" value="1"/>

            <input type="hidden" name="bn" value="ThePHPFactory_SP"/>

            <input type="image" src="<?php echo $this->getButton(); ?>" name="submit"/>
        </form>

        <?php

        return true;
    }

    public function processIpn()
    {
        $ipn = new JRegistry(JFilterInput::getInstance()->clean($_POST, null));

        // Preprocess ipn
        $ipn->set('user_id', $ipn->get('option_selection1'));
        $ipn->set('amount', $ipn->get('mc_gross'));
        $ipn->set('currency', $ipn->get('mc_currency'));
        $ipn->set('order_id', $ipn->get('item_number'));
        $ipn->set('refnumber', $ipn->get('txn_id'));

        // Create payment
        $payment = $this->createPayment($ipn);

        // Check for errors
        $errors = $this->validatePayment($ipn);

        switch ($ipn->get('payment_status')) {
            case 'Completed':
            case 'Processed':
                $payment->status = $errors ? 40 : 20; // 40 - Manual check; 20 - Completed
                break;

            case 'Failed':
            case 'Denied':
            case 'Canceled-Reversal':
            case 'Expired':
            case 'Voided':
            case 'Reversed':
            case 'Refunded':
                $payment->status = 30; // 30 - Failed
                break;

            case 'In-Progress':
            case 'Pending':
            default:
                $payment->status = 10; // 10 - Pending
                break;
        }

        $errors[] = 'Received Payment Status: ' . $ipn->get('payment_status');

        $this->savePayment($payment, $errors);
    }

    protected function validatePayment($ipn)
    {
        $errors = array();

        // Validate IPN
        if (true !== $this->validateIpn($ipn)) {
            $errors[] = JText::_('FACTORY_PAYMENT_PLUGIN_PAYPAL_VALIDATION_FAILED_IPN_NOT_VALIDATED');
        }

        // Validate paypal email
        if ($ipn->get('receiver_email') != $this->getParam('email')) {
            $errors[] = JText::_('FACTORY_PAYMENT_PLUGIN_PAYPAL_VALIDATION_FAILED_RECEIVER_EMAIL_IS_DIFFERENT');
        }

        // Validate user
        if (!$this->validateUser($ipn->get('user_id'))) {
            $errors[] = JText::_('FACTORY_PAYMENT_PLUGIN_PAYPAL_VALIDATION_FAILED_USER_NOT_VALIDATED');
        }

        // Validate Order
        $order = $this->findOrder($ipn->get('order_id'));

        if ($order) {
            // Validate amount
            if ($order->amount != $ipn->get('amount')) {
                $errors[] = JText::_('FACTORY_PAYMENT_PLUGIN_PAYPAL_VALIDATION_FAILED_AMOUNT_IS_DIFFERENT');
            }

            // Validate currency
            if ($order->currency != $ipn->get('currency')) {
                $errors[] = JText::_('FACTORY_PAYMENT_PLUGIN_PAYPAL_VALIDATION_FAILED_CURRENCY_IS_DIFFERENT');
            }
        } else {
            $errors[] = JText::_('FACTORY_PAYMENT_PLUGIN_PAYPAL_VALIDATION_FAILED_ORDER_NOT_FOUND');
        }

        return $errors;
    }

    protected function validateIpn()
    {
        // parse the paypal URL
        $url_parsed = parse_url($this->getAction());

        $post_string = '';
        foreach ($_POST as $field => $value) {
            $post_string .= $field . '=' . urlencode($value) . '&';
        }
        $post_string .= "cmd=_notify-validate";

        $fp = fsockopen('ssl://' . $url_parsed['host'], 443, $err_num, $err_str, 20);
        if (!$fp) {
            return 'Fsockopen error no. ' . $err_num . ': ' . $err_str . '. IPN not verified';
        } else {
            fputs($fp, "POST " . $url_parsed['path'] . " HTTP/1.1\r\n");
            fputs($fp, "Host: " . $url_parsed['host'] . ":443\r\n");
            fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
            fputs($fp, "Content-length: " . strlen($post_string) . "\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $post_string . "\r\n\r\n");

            $response = '';
            while (!feof($fp)) {
                $response .= fgets($fp, 1024);
            }

            fclose($fp);
        }

        if (preg_match('/VERIFIED/', $response)) {
            return true;
        }

        return false;
    }

    protected function getAction()
    {
        if ($this->getParam('use_sandbox', 1)) {
            return $this->getParam('action_sandbox', 'https://www.sandbox.paypal.com/cgi-bin/webscr');
        }

        return $this->getParam('action', 'https://www.paypal.com/cgi-bin/webscr');
    }
}
