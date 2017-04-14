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

class TargetPay extends factoryPaymentPlugin
{
    public function step1()
    {
        $Itemid = JFactory::getApplication()->input->getInt('Itemid');

        // Create banks list
        $banks = $this->createBanksList();

        ?>

        <h1><?php echo JText::_('Choose bank'); ?></h1>
        <p><?php echo JText::_('Chose the bank that you want to use'); ?>:</p>

        <br/>

        <form action="<?php echo JRoute::_('index.php'); ?>" method="post">
            <label for="bank"><?php echo JText::_('Bank'); ?>:</label>
            <?php echo JHTML::_('select.genericlist', $banks, 'bank'); ?>

            <br/>

            <input type="submit" value="<?php echo JText::_('Confirm'); ?>"/>

            <input type="hidden" name="option" value="com_lovefactory"/>
            <input type="hidden" name="controller" value="gateway"/>
            <input type="hidden" name="task" value="process"/>
            <input type="hidden" name="step" value="2"/>
            <input type="hidden" name="price" value="<?php echo $this->get('price_id'); ?>"/>
            <input type="hidden" name="method" value="<?php echo $this->getId(); ?>"/>
            <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
        </form>

        <?php

        return true;
    }

    public function step2()
    {
        // Create a new order
        if (!$this->createOrder()) {
            return false;
        }

        $url = 'https://www.targetpay.com/ideal/start';

        // Create params
        $params = array(
            'rtlo' => $this->getParam('layoutcode'),
            'bank' => JFactory::getApplication()->input->getInt('bank'),
            'description' => $this->get('order')->title,
            'currency' => $this->get('order')->currency,
            'amount' => $this->get('order')->amount * 100,
            'returnurl' => $this->get('url.complete'),
            'reporturl' => $this->get('url.notification'),
        );

        // Generate GET string
        $string = '';
        foreach ($params as $name => $value) {
            $string .= urlencode($name) . '=' . urlencode($value) . '&';
        }
        $string = trim($string, '&');

        // Get response
        $response = file_get_contents($url . '?' . $string);
        $aResponse = explode('|', $response);

        // Check if response is valid
        if (!isset($aResponse[1])) {
            $this->setError($aResponse[0]);

            return false;
        }

        $iTrxID = explode(' ', $aResponse[0]);

        $iTrxID = $iTrxID[1];
        $bankUrl = $aResponse[1];

        // Create payment
        $ipn = new JRegistry();

        $ipn->set('user_id', $this->get('order')->user_id);
        $ipn->set('amount', $this->get('order')->amount);
        $ipn->set('currency', $this->get('order')->currency);
        $ipn->set('order_id', $this->get('order')->id);
        $ipn->set('refnumber', $iTrxID);

        $payment = $this->createPayment($ipn);
        $payment->status = 10; // 10 - Pending
        $this->savePayment($payment);

        header('Location: ' . $bankUrl);

        return true;
    }

    public function processIpn()
    {
        if (substr($_SERVER['REMOTE_ADDR'], 0, 10) != "89.184.168" &&
            substr($_SERVER['REMOTE_ADDR'], 0, 9) != "78.152.58"
        ) {
            return false;
        }

        // Get values from request
        $this->trxid = JFactory::getApplication()->input->getString('trxid');
        $this->rtlo = JFactory::getApplication()->input->getString('rtlo');
        $this->status = JFactory::getApplication()->input->getString('status');

        // Get the payment
        $dbo = JFactory::getDBO();
        $query = $dbo->getQuery(true)
            ->select('p.*')
            ->from('#__lovefactory_payments p')
            ->where('p.refnumber = ' . $dbo->quote($this->trxid));
        $result = $dbo->setQuery($query)
            ->loadObject();

        if (!$result) {
            return false;
        }

        // Get the payment
        $payment = JTable::getInstance('Payment', 'Table');
        $payment->bind($result);

        $errors = array();

        // Check response status
        if (preg_match('/000000 OK/i', $this->status)) {
            $status = 'Completed';
        } else {
            $status = 'Failed';

            $errors = $this->status;
        }

        switch ($status) {
            case 'Completed':
                $payment->status = 20; // 20 - Completed
                break;

            default:
            case 'Failed':
                $payment->status = 30; // 30 - Failed
                break;
        }

        $this->savePayment($payment, $errors);
    }

    protected function createBanksList()
    {
        $options = array();
        $data = $this->getBankList();
        $xml = simplexml_load_string($data);

        foreach ($xml->issuer as $issuer) {
            $options[] = array('value' => (string)$issuer->attributes()->id, 'text' => (string)$issuer);
        }

        return $options;
    }

    protected function getBankList()
    {
        $url = 'https://www.targetpay.com/ideal/getissuers.php?format=xml';

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $data = curl_exec($ch);

            curl_close($ch);

            return $data;
        }

        if (ini_get('allow_url_fopen')) {
            $data = file_get_contents($url);

            return $data;
        }

        throw new Exception(JText::_('Unable to retrieve bank list from server!'), 500);

        return false;
    }
}
