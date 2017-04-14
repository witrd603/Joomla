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

class TablePayment extends JTable
{
    var $id = null;
    var $order_id = null;
    var $user_id = null;
    var $received_at = null;
    var $payment_date = null;
    var $amount = null;
    var $currency = null;
    var $gateway = null;
    var $refnumber = null;
    var $status = null;
    var $data = null;
    var $errors = null;

    public function __construct(&$db = null)
    {
        if (is_null($db)) {
            $db = JFactory::getDbo();
        }

        parent::__construct('#__lovefactory_payments', 'id', $db);
    }

    public function store($updateNulls = false)
    {
        $statusChanged = false;

        if (!$this->id) {
            $this->received_at = JFactory::getDate()->toUnix();
            $statusChanged = true;
        } else {
            $original = JTable::getInstance('Payment', 'Table');
            $original->load($this->id);

            if ($original->status != $this->status) {
                $statusChanged = true;
            }
        }

        if (!parent::store($updateNulls)) {
            return false;
        }

        JEventDispatcher::getInstance()->trigger('onLoveFactoryPaymentUpdated', array(
            'com_lovefactory.payment.updated', $this, $statusChanged
        ));

        return true;
    }

    public function getStatusLabel($status = null)
    {
        $labels = array(
            10 => JText::_('COM_LOVEFACTORY_PAYMENT_STATUS_PENDING'),
            20 => JText::_('COM_LOVEFACTORY_PAYMENT_STATUS_COMPLETED'),
            30 => JText::_('COM_LOVEFACTORY_PAYMENT_STATUS_FAILED'),
            40 => JText::_('COM_LOVEFACTORY_PAYMENT_STATUS_MANUAL_CHECK'),
        );

        return is_null($status) ? $labels : $labels[$status];
    }
}
