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

class IdealIng extends factoryPaymentPlugin
{
    public function step1()
    {
        // Create a new order
        if (!$this->createOrder()) {
            return false;
        }

        // Show the confirmation form
        ?>

        <h1><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_IDEALING_CONFIRM_PAGE_TITLE'); ?></h1>
        <p><?php echo JText::sprintf('FACTORY_PAYMENT_PLUGIN_IDEALING_CONFIRM_PAGE_TEXT', $this->get('order')->title); ?></p>

        <br/>

        <form action="<?php echo $this->getAction(); ?>" method="post">
            <input type="hidden" name="merchantID" value="<?php echo $this->getParam('merchant_id'); ?>">
            <input type="hidden" name="subID" value="<?php echo $this->getParam('sub_id'); ?>">
            <input type="hidden" name="amount" value="<?php echo round($this->get('order')->amount * 100); ?>">
            <input type="hidden" name="purchaseID" value="<?php echo $this->get('order')->id; ?>">
            <input type="hidden" name="language"
                   value="<?php echo JFactory::getApplication()->input->getCmd('lang', 'en'); ?>">
            <input type="hidden" name="currency" value="<?php echo $this->get('order')->currency; ?>">
            <input type="hidden" name="description" value="<?php echo $this->get('order')->title; ?>">
            <input type="hidden" name="hash" value="<?php echo $this->getShaString(); ?>">
            <input type="hidden" name="paymentType" value="ideal">
            <input type="hidden" name="validUntil" value="<?php echo date('Y-m-d\TG:i:s\Z', strtotime('+1 hour')); ?>">
            <input type="hidden" name="itemNumber1" value="<?php echo $this->get('order')->title; ?>">
            <input type="hidden" name="itemDescription1" value="<?php echo $this->get('order')->title; ?>">
            <input type="hidden" name="itemQuantity1" value="1">
            <input type="hidden" name="itemPrice1" value="<?php echo round($this->get('order')->amount * 100); ?>">
            <input type="hidden" name="urlSuccess" value="<?php echo $this->get('url.complete'); ?>">
            <input type="hidden" name="urlCancel" value="<?php echo $this->get('url.cancel'); ?>">
            <input type="hidden" name="urlError" value="<?php echo $this->get('url.failed'); ?>">

            <input type="submit" name="submit2"
                   value="<?php echo JText::_('FACTORY_PAYMENT_PLUGIN_IDEALING_FORM_SUBMIT'); ?>"/>
        </form>

        <?php

        return true;
    }

    protected function getAction()
    {
        if ($this->getParam('test_mode', 1)) {
            return 'https://idealtest.secure-ing.com/ideal/mpiPayInitIng.do';
        }

        return 'https://ideal.secure-ing.com/ideal/mpiPayInitIng.do';
    }

    protected function getShaString()
    {
        $shastring =
            $this->getParam('hash_key') .
            $this->getParam('merchant_id') .
            $this->getParam('sub_id') .
            round($this->get('order')->amount * 100) .
            $this->get('order')->id .
            'ideal' .
            date('Y-m-d\TG:i:s\Z', strtotime('+1 hour')) .
            $this->get('order')->title .
            $this->get('order')->title .
            1 .
            round($this->get('order')->amount * 100);

        # Replacement of ‘forbidden characters’
        $shastring = preg_replace(array("/[ \t\n]/", '/&amp;/i', '/&lt;/i', '/&gt;/i', '/&quot/i'), array('', '&', '<', '>', '"'), $shastring);

        # SHA1 calculation with the php formula sha1
        return sha1($shastring);
    }
}
