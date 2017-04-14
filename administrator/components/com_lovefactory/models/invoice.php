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

jimport('joomla.application.component.modeladmin');

class BackendModelInvoice extends JModelAdmin
{
    protected $option = 'com_lovefactory';

    public function getTable($type = 'Invoice', $prefix = 'Table', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm($this->option . '.' . $this->getName(), $this->getName(), array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function issue(LovefactorySettings $settings, TableOrder $order)
    {
        if (!$settings->enable_invoices) {
            return true;
        }

        JFactory::getLanguage()->load('com_lovefactory', JPATH_SITE);
        require_once JPATH_SITE . '/components/com_lovefactory/lib/vendor/page.php';
        require_once JPATH_SITE . '/components/com_lovefactory/lib/html/html.php';

        require_once JPATH_ADMINISTRATOR . '/components/com_lovefactory/settings.php';
        require_once JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/application.php';

        JLoader::register('LoveFactoryField', LoveFactoryApplication::getInstance()->getPath('path_administrator') . DS . 'lib' . DS . 'fields' . DS . 'field.php');

        $invoice = JTable::getInstance('Invoice', 'Table');

        $invoice->user_id = $order->user_id;
        $invoice->seller = $settings->invoice_template_seller;
        $invoice->buyer = $settings->invoice_template_buyer;
        $invoice->membership = $order->title;
        $invoice->price = 100 * $order->amount / ($settings->invoice_vat_rate + 100);
        $invoice->currency = $order->currency;
        $invoice->vat_rate = $settings->invoice_vat_rate;
        $invoice->vat_value = $invoice->price * $settings->invoice_vat_rate / 100;
        $invoice->total = $invoice->price + $invoice->vat_value;
        $invoice->issued_at = JFactory::getDate()->toUnix();

        if (preg_match_all('/%%([0-9]+)__.+%%/U', $invoice->buyer, $matches, PREG_SET_ORDER)) {
            $profile = JTable::getInstance('Profile', 'Table');
            $profile->load($invoice->user_id);

            foreach ($matches as $match) {
                $token = $match[0];
                $id = $match[1];

                $table = JTable::getInstance('Field', 'Table');

                if (!$id || !$table->load($id)) {
                    continue;
                }

                // Filter out some field types.
                if (in_array($table->type, array('Password', 'Price'))) {
                    $replace = '';
                } else {
                    $field = LoveFactoryField::getInstance($table->type, $table);
                    $field->bind($profile);

                    $replace = $field->renderInputView();
                }

                $invoice->buyer = str_replace($token, $replace, $invoice->buyer);
            }
        }

        return $invoice->store();
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.' . $this->getName() . '.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }
}
