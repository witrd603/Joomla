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

class Offline extends factoryPaymentPlugin
{
    public function step1()
    {
        // Create a new order
        if (!$this->createOrder()) {
            return false;
        }

        if (2 == $this->getParam('email_notification', 0)) {
            $text = JText::_('FACTORY_PAYMENT_PLUGIN_OFFLINE_PAGE_TEXT_EMAIL_NOTIFICATION');
        } else {
            $text = JText::sprintf('FACTORY_PAYMENT_PLUGIN_OFFLINE_PAGE_TEXT_NOTIFICATION', $this->order->id, $this->getParam('bank_details'));
        }

        ?>

        <h1><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_OFFLINE_PAGE_TITLE'); ?></h1>
        <p><?php echo nl2br($text); ?></p>

        <?php

        if ($this->getParam('email_notification', 0)) {
            $mailer = FactoryMailer::getInstance();
            $mailer->send(
                'offline_payment',
                $this->order->user_id,
                array(
                    'receiver_username' => JFactory::getUser($this->order->user_id)->username,
                    'order_id' => $this->order->id,
                    'bank_details' => $this->getParam('bank_details'),
                ));
        }

        return true;
    }
}
