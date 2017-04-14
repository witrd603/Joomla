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

class TableOrder extends JTable
{
    public $id;
    public $membership;
    public $price;
    public $status;
    public $created_at;

    public function __construct(&$db)
    {
        parent::__construct('#__lovefactory_orders', 'id', $db);
    }

    public function createFrom($config)
    {
        $settings = new LoveFactorySettings();
        $genderString = '';

        if ($settings->gender_pricing) {
            if (!$config['price']->hasGender($config['profile']->sex)) {
                return false;
            }

            $this->amount = $config['price']->getGenderPrice($config['profile']->sex);

            $model = JModelLegacy::getInstance('Memberships', 'FrontendModel');
            $genders = $model->getAvailableGenders();
            $genderString = $genders[$config['profile']->sex];
        } else {
            $this->amount = $config['price']->price;
        }

        $this->user_id = $config['profile']->user_id;
        $this->membership_id = $config['membership']->id;
        $this->price_id = $config['price']->id;
        $this->currency = $config['settings']->currency;
        $this->gateway = $config['gateway'];
        $this->paid = 0;
        $this->status = 10; // Pending

        JLoader::register('JHtmlLoveFactory', JPATH_SITE . '/components/com_lovefactory/lib/html/html.php');
        $currency = JHtml::_('LoveFactory.currency', $this->amount, $this->currency);

        $this->title = FactoryText::plural(
            'order_create_title',
            $config['price']->months,
            $config['membership']->title,
            $genderString,
            $currency
        );

        $membership = new JRegistry($config['membership']);
        $this->membership = $membership->toString();

        $price = new JRegistry($config['price']);
        $this->price = $price->toString();

        if (!$this->store()) {
            return false;
        }

        // Trigger new order created event.
        JEventDispatcher::getInstance()->trigger('onLoveFactoryOrderCreate', array(
            'com_lovefactory.order.create',
            $this,
        ));

        return $this;
    }

    public function store($updateNulls = false)
    {
        if (!$this->id) {
            $this->created_at = JFactory::getDate()->toUnix();
            $statusChanged = true;
        } else {
            $original = JTable::getInstance('Order', 'Table');
            $original->load($this->id);

            $statusChanged = $original->status != $this->status;
        }

        if (!parent::store($updateNulls)) {
            return false;
        }

        // If status has changed to completed, trigger order completed event.
        if ($statusChanged && $this->isCompleted()) {
            JEventDispatcher::getInstance()->trigger('onLoveFactoryOrderCompleted', array(
                'com_lovefactory.order_completed', $this
            ));
        }

        return true;
    }

    public function getStatusLabel()
    {
        $labels = array(
            10 => JText::_('COM_LOVEFACTORY_ORDER_STATUS_PENDING'),
            20 => JText::_('COM_LOVEFACTORY_ORDER_STATUS_COMPLETED'),
            30 => JText::_('COM_LOVEFACTORY_ORDER_STATUS_FAILED'),
            40 => JText::_('COM_LOVEFACTORY_ORDER_STATUS_MANUAL_CHECK'),
        );

        return $labels;
    }

    public function updateFromPaymentStatus($paymentStatus)
    {
        if (in_array($this->status, array(20, 30))) {
            return false;
        }

        switch ($paymentStatus) {
            case 10: // Pending
                $this->status = 10;
                break;

            case 20: // Completed
                $this->status = 20;
                $this->paid = 1;
                break;

            case 30: // Failed
                $this->status = 30;
                $this->paid = 0;
                break;

            case 40: // Manual check
                $this->status = 10;
                break;
        }

        return $this->store();
    }

    public function isCompleted()
    {
        return 20 == $this->status;
    }

    public function changeStatus($status)
    {
        // Check if order is already marked as completed.
//    if (in_array($this->status, array(20))) {
//      $this->setError(JText::sprintf('COM_LOVEFACTORY_ORDERS_COMPLETED_ORDER_CANNOT_BE_UPDATED', $this->id));
//      return false;
//    }

        // Change status.
        $this->status = $status;

        return $this->store();
    }

    public function getMembership()
    {
        /** @var TableMembership $membership */
        $membership = JTable::getInstance('Membership', 'Table');

        $registry = new \Joomla\Registry\Registry($this->membership);
        $membership->bind($registry->toArray());

        return $membership;
    }

    public function getPrice()
    {
        /** @var TablePrice $price */
        $price = JTable::getInstance('Price', 'Table');

        $registry = new \Joomla\Registry\Registry($this->price);
        $price->bind($registry->toArray());

        return $price;
    }
}
