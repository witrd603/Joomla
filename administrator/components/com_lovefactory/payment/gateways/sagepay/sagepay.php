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

class Sagepay extends factoryPaymentPlugin
{
    function step1()
    {
        $vars = 'GET' == $_SERVER['REQUEST_METHOD'] ? @$_SESSION['sagepay_vars'] : array();
        $Itemid = JFactory::getApplication()->input->getInt('Itemid');
        ?>

        <script>

            <?php $this->writeJavascriptCountries(); ?>

            function getCountryOptionsListHtml(strSelectedValue) {
                var strCountryOptionsList = '';
                for (var i = 0; i < countries.length; i++) {
                    strCountryOptionsList += '<option value="' + countries[i].code + '"'
                    if (strSelectedValue == countries[i].code) {
                        strCountryOptionsList += " SELECTED"
                    }
                    strCountryOptionsList += ">" + countries[i].name + "</option>\n";
                }

                return strCountryOptionsList;
            }

            function validation() {
                name = document.getElementById("BillingFirstnames").value;
                surname = document.getElementById("BillingSurname").value;
                address = document.getElementById("BillingAddress1").value;
                city = document.getElementById("BillingCity").value;
                code = document.getElementById("BillingPostCode").value;
                country = document.getElementById("BillingCountry").selectedIndex;

                error = '';

                if ('' == name) {
                    error += "<?php echo addslashes(JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_ENTER_FIRST_NAME')); ?>" + "\n";
                }

                if ('' == surname) {
                    error += "<?php echo addslashes(JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_ENTER_SURNAME')); ?>" + "\n";
                }

                if ('' == address) {
                    error += "<?php echo addslashes(JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_ENTER_ADDRESS')); ?>" + "\n";
                }

                if ('' == city) {
                    error += "<?php echo addslashes(JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_ENTER_CITY')); ?>" + "\n";
                }

                if ('' == code) {
                    error += "<?php echo addslashes(JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_ENTER_ZIP')); ?>" + "\n";
                }

                if (0 == country) {
                    error += "<?php echo addslashes(JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_SELECT_COUNTRY')); ?>" + "\n";
                }

                if ('' != error) {
                    alert(error);
                    return false;
                }

                return true;
            }
        </script>

        <form action="<?php echo JRoute::_('index.php'); ?>" method="POST" onsubmit="return validation();">

            <h1><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_DETAILS'); ?></h1>

            <table>

                <!-- Billing details -->
                <tr>
                    <th colspan="2"><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_BILLINT_DETAILS'); ?></th>
                </tr>

                <tr>
                    <td><label for="BillingFirstnames"><span
                                class="warning">*</span><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_FIRST_NAME'); ?>
                            :</label></td>
                    <td><input id="BillingFirstnames" name="BillingFirstnames" type="text" maxlength="20"
                               value="<?php echo @$vars['BillingFirstnames']; ?>"/></td>
                </tr>

                <tr>
                    <td><label for="BillingSurname"><span
                                class="warning">*</span><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_SURNAME'); ?>
                            :</label></td>
                    <td><input id="BillingSurname" name="BillingSurname" type="text" maxlength="20"
                               value="<?php echo @$vars['BillingSurname']; ?>"/></td>
                </tr>

                <tr>
                    <td><label for="BillingAddress1"><span
                                class="warning">*</span><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_ADDRESS_LINE_1'); ?>
                            :</label></td>
                    <td><input id="BillingAddress1" name="BillingAddress1" type="text" maxlength="100"
                               value="<?php echo @$vars['BillingAddress1']; ?>"/></td>
                </tr>

                <tr>
                    <td><label
                            for="BillingAddress2"><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_ADDRESS_LINE_2'); ?>
                            :</label></td>
                    <td><input id="BillingAddress2" name="BillingAddress2" type="text" maxlength="100"
                               value="<?php echo @$vars['BillingAddress2']; ?>"/></td>
                </tr>

                <tr>
                    <td><label for="BillingCity"><span
                                class="warning">*</span><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_CITY'); ?>:</label>
                    </td>
                    <td><input id="BillingCity" name="BillingCity" type="text" maxlength="40"
                               value="<?php echo @$vars['BillingCity']; ?>"/></td>
                </tr>

                <tr>
                    <td><label for="BillingPostCode"><span
                                class="warning">*</span><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_ZIP'); ?>
                            :</label></td>
                    <td><input id="BillingPostCode" name="BillingPostCode" type="text" maxlength="10"
                               value="<?php echo @$vars['BillingPostCode']; ?>"/></td>
                </tr>

                <tr>
                    <td><label for="BillingCountry"><span
                                class="warning">*</span><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_COUNTRY'); ?>
                            :</label></td>
                    <td>
                        <select id="BillingCountry" name="BillingCountry" style="width: 200px;">
                            <option value=""><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_SELECT'); ?></option>
                            <script type="text/javascript" language="javascript">
                                document.write(getCountryOptionsListHtml("<?php echo @$vars['BillingCountry']; ?>"));
                            </script>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><label for="BillingState"><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_STATE'); ?>
                            :</label></td>
                    <td><input id="BillingState" name="BillingState" type="text" maxlength="2"
                               value="<?php echo @$vars['BillingState']; ?>"/></td>
                </tr>

                <tr>
                    <td><label for="BillingPhone"><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_PHONE'); ?>
                            :</label></td>
                    <td><input id="BillingPhone" name="BillingPhone" type="text" maxlength="20"
                               value="<?php echo @$vars['BillingPhone']; ?>"/></td>
                </tr>
            </table>

            <input type="submit" value="<?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_NEXT_STEP'); ?>"/>

            <input type="hidden" name="option" value="com_lovefactory"/>
            <input type="hidden" name="controller" value="gateway"/>
            <input type="hidden" name="task" value="process"/>
            <input type="hidden" name="step" value="2"/>
            <input type="hidden" name="method" value="<?php echo $this->getId(); ?>"/>
            <input type="hidden" name="price" value="<?php echo $this->get('price_id'); ?>"/>
            <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
        </form>

        <?php

        return true;
    }

    function step2()
    {
        // Get the values
        $vars = array();

        $IsDeliverySame = 'YES';

        $vars['BillingFirstnames'] = $this->cleanInput(JFactory::getApplication()->input->getString('BillingFirstnames', ''), 'Text');
        $vars['BillingSurname'] = $this->cleaninput(JFactory::getApplication()->input->getString('BillingSurname', ''), 'Text');
        $vars['BillingAddress1'] = $this->cleaninput(JFactory::getApplication()->input->getString('BillingAddress1', ''), 'Text');
        $vars['BillingAddress2'] = $this->cleaninput(JFactory::getApplication()->input->getString('BillingAddress2', ''), 'Text');
        $vars['BillingCity'] = $this->cleaninput(JFactory::getApplication()->input->getString('BillingCity', ''), 'Text');
        $vars['BillingPostCode'] = $this->cleaninput(JFactory::getApplication()->input->getString('BillingPostCode', ''), 'Text');
        $vars['BillingCountry'] = $this->cleaninput(JFactory::getApplication()->input->getString('BillingCountry', ''), 'Text');
        $vars['BillingState'] = $this->cleaninput(JFactory::getApplication()->input->getString('BillingState', ''), 'Text');
        $vars['BillingPhone'] = $this->cleaninput(JFactory::getApplication()->input->getString('BillingPhone', ''), 'Text');

        $vars['DeliveryFirstnames'] = $vars['BillingFirstnames'];
        $vars['DeliverySurname'] = $vars['BillingSurname'];
        $vars['DeliveryAddress1'] = $vars['BillingAddress1'];
        $vars['DeliveryAddress2'] = $vars['BillingAddress2'];
        $vars['DeliveryCity'] = $vars['BillingCity'];
        $vars['DeliveryPostCode'] = $vars['BillingPostCode'];
        $vars['DeliveryCountry'] = $vars['BillingCountry'];
        $vars['DeliveryState'] = $vars['BillingState'];
        $vars['DeliveryPhone'] = $vars['BillingPhone'];

        // Check for errors
        $error = $this->validateSagePay($vars);

        // If errors were found, show the form again
        if ($error != '') {
            // Store the values in session, so we can fill in the fields
            $_SESSION['sagepay_vars'] = $vars;

            $this->setError($error);
            return false;
        }

        // Create new order
        if (!$this->createOrder()) {
            return false;
        }

        // Create the crypt
        $crypt = $this->createCrypt($vars);

        $order = $this->get('order');
        // Show the confirmation form
        ?>
        <script>

            <?php $this->writeJavascriptCountries(); ?>
            function getCountryName(strCountryCode) {
                for (var i = 0; i < countries.length; i++) {
                    if (strCountryCode == countries[i].code) {
                        return countries[i].name;
                    }
                }

                return "";
            }
        </script>

        <h1><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_CONFIRM'); ?></h1>
        <p><?php echo JText::sprintf('FACTORY_PAYMENT_PLUGIN_SAGEPAY_PURCHASE_CONFIRM', $order->title); ?></p>

        <table>
            <!-- Billing details -->
            <tr>
                <th colspan="2"><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_BILLING_DETAILS'); ?></th>
            </tr>

            <tr>
                <td><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_FIRST_NAME'); ?>:</td>
                <td><?php echo $vars['BillingFirstnames']; ?></td>
            </tr>

            <tr>
                <td><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_SURNAME'); ?>:</td>
                <td><?php echo $vars['BillingSurname']; ?></td>
            </tr>

            <tr>
                <td><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_ADDRESS_LINE_1'); ?>:</td>
                <td><?php echo $vars['BillingAddress1']; ?></td>
            </tr>

            <tr>
                <td><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_ADDRESS_LINE_2'); ?>:</td>
                <td><?php echo $vars['BillingAddress2']; ?></td>
            </tr>

            <tr>
                <td><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_CITY'); ?>:</td>
                <td><?php echo $vars['BillingCity']; ?></td>
            </tr>

            <tr>
                <td><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_ZIP'); ?>:</td>
                <td><?php echo $vars['BillingPostCode']; ?></td>
            </tr>

            <tr>
                <td><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_COUNTRY'); ?>:</td>
                <td>
                    <script type="text/javascript" language="javascript">
                        document.write(getCountryName("<?php echo $vars['BillingCountry']; ?>"));
                    </script>
                </td>
            </tr>

            <tr>
                <td><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_STATE'); ?>:</td>
                <td><?php echo $vars['BillingState']; ?></td>
            </tr>

            <tr>
                <td><?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_PHONE'); ?>:</td>
                <td><?php echo $vars['BillingPhone']; ?></td>
            </tr>
        </table>

        <form action="<?php echo $this->getAction(); ?>" method="POST" id="SagePayForm" name="SagePayForm">
            <input type="hidden" name="navigate" value=""/>
            <input type="hidden" name="VPSProtocol" value="2.23">
            <input type="hidden" name="TxType" value="PAYMENT">
            <input type="hidden" name="Vendor" value="<?php echo $this->getParam('vendor_name'); ?>">
            <input type="hidden" name="Crypt" value="<?php echo $crypt; ?>">

            <input type="submit" value="<?php echo JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_SUBMIT'); ?>"/>
        </form>

        <?php

        return true;
    }

    function processIpn()
    {
        $crypt = JFactory::getApplication()->input->getString('crypt');
        $decrypted = $this->simpleXor($this->base64Decode($crypt), $this->getParam('encryption_password'));
        $exploded = explode('&', $decrypted);

        $ipn = new JRegistry();
        foreach ($exploded as $field) {
            $field = explode('=', $field);
            $vars[$field[0]] = $field[1];
            $ipn->set($field[0], $field[1]);
        }

        $ipn->set('payment_date', JFactory::getDate()->toSql());
        $ipn->set('user_id', JFactory::getUser()->id);
        $ipn->set('amount', $ipn->get('Amount'));
        $ipn->set('order_id', $ipn->get('VendorTxCode'));
        $ipn->set('refnumber', $ipn->get('VPSTxId'));
        $ipn->set('status', $ipn->get('Status'));

        // Create payment
        $payment = $this->createPayment($ipn);

        $errors = array();

        // Validate Order
        $order = $this->findOrder($ipn->get('order_id'));

        if ($order) {
            // We found the order
            $ipn->set('currency', $order->currency);

            switch ($ipn->get('status')) {
                case 'OK':
                    $payment->status = 20; // 20 - Completed
                    break;

                default:
                    $errors[] = $ipn->get('StatusDetail');
                    $payment->status = 30; // 30 - Failed
                    break;
            }
        } else {
            $payment->status = 40; // 40 - Manual check
        }

        $this->savePayment($payment, $errors);

        // Show the response
        switch ($payment->status) {
            case 20:
                header('Location: ' . $this->get('url.complete'));
                break;

            default:
                JFactory::getSession()->set('com_lovefactory.payment.error', $ipn->get('Status') . ' (' . $ipn->get('StatusDetail') . ')');
                header('Location: ' . $this->get('url.failed'));
                break;
        }
    }

    protected function getAction()
    {
        switch ($this->getParam('mode')) {
            case 0:
            default:
                return $this->getParam('action_live');

            case 1:
                return $this->getParam('action_test');

            case 2:
                return $this->getParam('action_simulator');
        }
    }

    protected function cleanInput($strRawText, $strType)
    {
        if ($strType == "Number") {
            $strClean = "0123456789.";
            $bolHighOrder = false;
        } else if ($strType == "VendorTxCode") {
            $strClean = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
            $bolHighOrder = false;
        } else {
            $strClean = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.,'/{}@():?-_&�$=%~<>*+\"";
            $bolHighOrder = true;
        }

        $strCleanedText = "";
        $iCharPos = 0;

        do {
            // Only include valid characters
            $chrThisChar = substr($strRawText, $iCharPos, 1);

            if (strspn($chrThisChar, $strClean, 0, strlen($strClean)) > 0) {
                $strCleanedText = $strCleanedText . $chrThisChar;
            } else if ($bolHighOrder == true) {
                // Fix to allow accented characters and most high order bit chars which are harmless
                if (bin2hex($chrThisChar) >= 191) {
                    $strCleanedText = $strCleanedText . $chrThisChar;
                }
            }

            $iCharPos = $iCharPos + 1;
        } while ($iCharPos < strlen($strRawText));

        $cleanInput = ltrim($strCleanedText);
        return $cleanInput;

    }

    protected function validateSagePay($sagepay_vars)
    {
        $bIsDeliverySame = JFactory::getApplication()->input->getString('IsDeliverySame');
        $bIsDeliverySame = ($bIsDeliverySame == 'YES') ? true : false;

        // Validate the compulsory fields
        $error = '';
        if (strlen($sagepay_vars['BillingFirstnames']) == 0)
            $error = JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_ENTER_FIRST_NAME');
        else if (strlen($sagepay_vars['BillingSurname']) == 0)
            $error = JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_ENTER_SURNAME');
        else if (strlen($sagepay_vars['BillingAddress1']) == 0)
            $error = JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_ENTER_ADDRESS');
        else if (strlen($sagepay_vars['BillingCity']) == 0)
            $error = JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_ENTER_CITY');
        else if (strlen($sagepay_vars['BillingPostCode']) == 0)
            $error = JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_ENTER_ZIP');
        else if (strlen($sagepay_vars['BillingCountry']) == 0)
            $error = JText::_('FACTORY_PAYMENT_PLUGIN_SAGEPAY_SELECT_COUNTRY');
        else if ((strlen($sagepay_vars['BillingState']) == 0) and ($sagepay_vars['BillingCountry'] == "US"))
            $error = "Please enter your State code as you have selected United States for billing country.";
        else if (($bIsDeliverySame == false) and strlen($sagepay_vars['DeliveryFirstnames']) == 0)
            $error = "Please enter your Delivery First Names(s) where requested below.";
        else if (($bIsDeliverySame == false) and strlen($sagepay_vars['DeliverySurname']) == 0)
            $error = "Please enter your Delivery Surname where requested below.";
        else if (($bIsDeliverySame == false) and strlen($sagepay_vars['DeliveryAddress1']) == 0)
            $error = "Please enter your Delivery Address Line 1 where requested below.";
        else if (($bIsDeliverySame == false) and strlen($sagepay_vars['DeliveryCity']) == 0)
            $error = "Please enter your Delivery City where requested below.";
        else if (($bIsDeliverySame == false) and strlen($sagepay_vars['DeliveryPostCode']) == 0)
            $error = "Please enter your Delivery Post Code where requested below.";
        else if (($bIsDeliverySame == false) and strlen($sagepay_vars['DeliveryCountry']) == 0)
            $error = "Please select your Delivery Country where requested below.";
        else if (($bIsDeliverySame == false) and (strlen($sagepay_vars['DeliveryState']) == 0) and ($sagepay_vars['DeliveryCountry'] == "US"))
            $error = "Please enter your State code as you have selected United States for delivery country.";

        $error = JText::_($error);

        return $error;
    }

    protected function createCrypt($vars)
    {
        $order = $this->get('order');
        $strPost = 'VendorTxCode=' . $order->id;

        $strPost .= '&ReferrerID=' . $this->getParam('referer_id');
        $strPost .= '&Amount=' . $order->amount;
        $strPost .= '&Currency=' . $order->currency;
        $strPost .= '&Description=' . $order->title;

        $strPost .= '&SuccessURL=' . $this->get('url.notification');
        $strPost .= '&FailureURL=' . $this->get('url.notification');

        $strPost .= '&CustomerName=' . $vars['BillingFirstnames'] . ' ' . $vars['BillingSurname'];
        $strPost .= '&CustomerEMail=' . JFactory::getUser($order->user_id)->email;
        $strPost .= '&VendorEMail=' . $this->getParam('confirmation_email', '');
        $strPost .= '&SendEMail=' . $this->getParam('send_email', '');
        $strPost .= '&eMailMessage=' . $this->getParam('email_message', '');

        // Billing Details:
        $strPost .= '&BillingFirstnames=' . $vars['BillingFirstnames'];
        $strPost .= '&BillingSurname=' . $vars['BillingSurname'];
        $strPost .= '&BillingAddress1=' . $vars['BillingAddress1'];

        if (strlen($vars['BillingAddress2']) > 0) {
            $strPost .= '&BillingAddress2=' . $vars['BillingAddress2'];
        }

        $strPost .= '&BillingCity=' . $vars['BillingCity'];
        $strPost .= '&BillingPostCode=' . $vars['BillingPostCode'];
        $strPost .= '&BillingCountry=' . $vars['BillingCountry'];

        if (strlen($vars['BillingState']) > 0) {
            $strPost .= '&BillingState=' . $vars['BillingState'];
        }

        if (strlen($vars['BillingPhone']) > 0) {
            $strPost .= '&BillingPhone=' . $vars['BillingPhone'];
        }

        // Delivery Details:
        $strPost .= '&DeliveryFirstnames=' . $vars['DeliveryFirstnames'];
        $strPost .= '&DeliverySurname=' . $vars['DeliverySurname'];
        $strPost .= '&DeliveryAddress1=' . $vars['DeliveryAddress1'];

        if (strlen($vars['DeliveryAddress2']) > 0) {
            $strPost .= '&DeliveryAddress2=' . $vars['DeliveryAddress2'];
        }

        $strPost .= '&DeliveryCity=' . $vars['DeliveryCity'];
        $strPost .= '&DeliveryPostCode=' . $vars['DeliveryPostCode'];
        $strPost .= '&DeliveryCountry=' . $vars['DeliveryCountry'];

        if (strlen($vars['DeliveryState']) > 0) {
            $strPost .= '&DeliveryState=' . $vars['DeliveryState'];
        }

        if (strlen($vars['DeliveryPhone']) > 0) {
            $strPost .= '&DeliveryPhone=' . $vars['DeliveryPhone'];
        }

        // Basket
        $strPost .= '&Basket=' . '1:' . $order->title . ':1:' . $order->amount . ':0:' . $order->amount . ':' . $order->amount;

        $strPost .= '&AllowGiftAid=' . $this->getParam('allow_gift_aid', '');
        $strPost .= '&ApplyAVSCV2=' . $this->getParam('apply_avscv2', '');
        $strPost .= '&Apply3DSecure=' . $this->getParam('apply_3dsecure', '');

        $strCrypt = $this->base64Encode($this->SimpleXor($strPost, $this->getParam('encryption_password', '')));

        return $strCrypt;
    }

    protected function base64Encode($plain)
    {
        // Initialise output variable
        $output = "";

        // Do encoding
        $output = base64_encode($plain);

        // Return the result
        return $output;
    }

    protected function base64Decode($scrambled)
    {
        // Initialise output variable
        $output = "";

        // Fix plus to space conversion issue
        $scrambled = str_replace(" ", "+", $scrambled);

        // Do encoding
        $output = base64_decode($scrambled);

        // Return the result
        return $output;
    }

    protected function simpleXor($InString, $Key)
    {
        // Initialise key array
        $KeyList = array();
        // Initialise out variable
        $output = "";

        // Convert $Key into array of ASCII values
        for ($i = 0; $i < strlen($Key); $i++) {
            $KeyList[$i] = ord(substr($Key, $i, 1));
        }

        // Step through string a character at a time
        for ($i = 0; $i < strlen($InString); $i++) {
            // Get ASCII code from string, get ASCII code from key (loop through with MOD), XOR the two, get the character from the result
            // % is MOD (modulus), ^ is XOR
            $output .= chr(ord(substr($InString, $i, 1)) ^ ($KeyList[$i % strlen($Key)]));
        }

        // Return the result
        return $output;
    }

    protected function writeJavascriptCountries()
    {
        ?>
        // ISO 3166-1 country names and codes from http://opencountrycodes.appspot.com/javascript
        countries = [
        {code: "GB", name: "United Kingdom"},
        {code: "AF", name: "Afghanistan"},
        {code: "AX", name: "Aland Islands"},
        {code: "AL", name: "Albania"},
        {code: "DZ", name: "Algeria"},
        {code: "AS", name: "American Samoa"},
        {code: "AD", name: "Andorra"},
        {code: "AO", name: "Angola"},
        {code: "AI", name: "Anguilla"},
        {code: "AQ", name: "Antarctica"},
        {code: "AG", name: "Antigua and Barbuda"},
        {code: "AR", name: "Argentina"},
        {code: "AM", name: "Armenia"},
        {code: "AW", name: "Aruba"},
        {code: "AU", name: "Australia"},
        {code: "AT", name: "Austria"},
        {code: "AZ", name: "Azerbaijan"},
        {code: "BS", name: "Bahamas"},
        {code: "BH", name: "Bahrain"},
        {code: "BD", name: "Bangladesh"},
        {code: "BB", name: "Barbados"},
        {code: "BY", name: "Belarus"},
        {code: "BE", name: "Belgium"},
        {code: "BZ", name: "Belize"},
        {code: "BJ", name: "Benin"},
        {code: "BM", name: "Bermuda"},
        {code: "BT", name: "Bhutan"},
        {code: "BO", name: "Bolivia"},
        {code: "BA", name: "Bosnia and Herzegovina"},
        {code: "BW", name: "Botswana"},
        {code: "BV", name: "Bouvet Island"},
        {code: "BR", name: "Brazil"},
        {code: "IO", name: "British Indian Ocean Territory"},
        {code: "BN", name: "Brunei Darussalam"},
        {code: "BG", name: "Bulgaria"},
        {code: "BF", name: "Burkina Faso"},
        {code: "BI", name: "Burundi"},
        {code: "KH", name: "Cambodia"},
        {code: "CM", name: "Cameroon"},
        {code: "CA", name: "Canada"},
        {code: "CV", name: "Cape Verde"},
        {code: "KY", name: "Cayman Islands"},
        {code: "CF", name: "Central African Republic"},
        {code: "TD", name: "Chad"},
        {code: "CL", name: "Chile"},
        {code: "CN", name: "China"},
        {code: "CX", name: "Christmas Island"},
        {code: "CC", name: "Cocos (Keeling) Islands"},
        {code: "CO", name: "Colombia"},
        {code: "KM", name: "Comoros"},
        {code: "CG", name: "Congo"},
        {code: "CD", name: "Congo, The Democratic Republic of the"},
        {code: "CK", name: "Cook Islands"},
        {code: "CR", name: "Costa Rica"},
        {code: "CI", name: "Côte d'Ivoire"},
        {code: "HR", name: "Croatia"},
        {code: "CU", name: "Cuba"},
        {code: "CY", name: "Cyprus"},
        {code: "CZ", name: "Czech Republic"},
        {code: "DK", name: "Denmark"},
        {code: "DJ", name: "Djibouti"},
        {code: "DM", name: "Dominica"},
        {code: "DO", name: "Dominican Republic"},
        {code: "EC", name: "Ecuador"},
        {code: "EG", name: "Egypt"},
        {code: "SV", name: "El Salvador"},
        {code: "GQ", name: "Equatorial Guinea"},
        {code: "ER", name: "Eritrea"},
        {code: "EE", name: "Estonia"},
        {code: "ET", name: "Ethiopia"},
        {code: "FK", name: "Falkland Islands (Malvinas)"},
        {code: "FO", name: "Faroe Islands"},
        {code: "FJ", name: "Fiji"},
        {code: "FI", name: "Finland"},
        {code: "FR", name: "France"},
        {code: "GF", name: "French Guiana"},
        {code: "PF", name: "French Polynesia"},
        {code: "TF", name: "French Southern Territories"},
        {code: "GA", name: "Gabon"},
        {code: "GM", name: "Gambia"},
        {code: "GE", name: "Georgia"},
        {code: "DE", name: "Germany"},
        {code: "GH", name: "Ghana"},
        {code: "GI", name: "Gibraltar"},
        {code: "GR", name: "Greece"},
        {code: "GL", name: "Greenland"},
        {code: "GD", name: "Grenada"},
        {code: "GP", name: "Guadeloupe"},
        {code: "GU", name: "Guam"},
        {code: "GT", name: "Guatemala"},
        {code: "GG", name: "Guernsey"},
        {code: "GN", name: "Guinea"},
        {code: "GW", name: "Guinea-Bissau"},
        {code: "GY", name: "Guyana"},
        {code: "HT", name: "Haiti"},
        {code: "HM", name: "Heard Island and McDonald Islands"},
        {code: "VA", name: "Holy See (Vatican City State)"},
        {code: "HN", name: "Honduras"},
        {code: "HK", name: "Hong Kong"},
        {code: "HU", name: "Hungary"},
        {code: "IS", name: "Iceland"},
        {code: "IN", name: "India"},
        {code: "ID", name: "Indonesia"},
        {code: "IR", name: "Iran, Islamic Republic of"},
        {code: "IQ", name: "Iraq"},
        {code: "IE", name: "Ireland"},
        {code: "IM", name: "Isle of Man"},
        {code: "IL", name: "Israel"},
        {code: "IT", name: "Italy"},
        {code: "JM", name: "Jamaica"},
        {code: "JP", name: "Japan"},
        {code: "JE", name: "Jersey"},
        {code: "JO", name: "Jordan"},
        {code: "KZ", name: "Kazakhstan"},
        {code: "KE", name: "Kenya"},
        {code: "KI", name: "Kiribati"},
        {code: "KP", name: "Korea, Democratic People's Republic of"},
        {code: "KR", name: "Korea, Republic of"},
        {code: "KW", name: "Kuwait"},
        {code: "KG", name: "Kyrgyzstan"},
        {code: "LA", name: "Lao People's Democratic Republic"},
        {code: "LV", name: "Latvia"},
        {code: "LB", name: "Lebanon"},
        {code: "LS", name: "Lesotho"},
        {code: "LR", name: "Liberia"},
        {code: "LY", name: "Libyan Arab Jamahiriya"},
        {code: "LI", name: "Liechtenstein"},
        {code: "LT", name: "Lithuania"},
        {code: "LU", name: "Luxembourg"},
        {code: "MO", name: "Macao"},
        {code: "MK", name: "Macedonia, The Former Yugoslav Republic of"},
        {code: "MG", name: "Madagascar"},
        {code: "MW", name: "Malawi"},
        {code: "MY", name: "Malaysia"},
        {code: "MV", name: "Maldives"},
        {code: "ML", name: "Mali"},
        {code: "MT", name: "Malta"},
        {code: "MH", name: "Marshall Islands"},
        {code: "MQ", name: "Martinique"},
        {code: "MR", name: "Mauritania"},
        {code: "MU", name: "Mauritius"},
        {code: "YT", name: "Mayotte"},
        {code: "MX", name: "Mexico"},
        {code: "FM", name: "Micronesia, Federated States of"},
        {code: "MD", name: "Moldova"},
        {code: "MC", name: "Monaco"},
        {code: "MN", name: "Mongolia"},
        {code: "ME", name: "Montenegro"},
        {code: "MS", name: "Montserrat"},
        {code: "MA", name: "Morocco"},
        {code: "MZ", name: "Mozambique"},
        {code: "MM", name: "Myanmar"},
        {code: "NA", name: "Namibia"},
        {code: "NR", name: "Nauru"},
        {code: "NP", name: "Nepal"},
        {code: "NL", name: "Netherlands"},
        {code: "AN", name: "Netherlands Antilles"},
        {code: "NC", name: "New Caledonia"},
        {code: "NZ", name: "New Zealand"},
        {code: "NI", name: "Nicaragua"},
        {code: "NE", name: "Niger"},
        {code: "NG", name: "Nigeria"},
        {code: "NU", name: "Niue"},
        {code: "NF", name: "Norfolk Island"},
        {code: "MP", name: "Northern Mariana Islands"},
        {code: "NO", name: "Norway"},
        {code: "OM", name: "Oman"},
        {code: "PK", name: "Pakistan"},
        {code: "PW", name: "Palau"},
        {code: "PS", name: "Palestinian Territory, Occupied"},
        {code: "PA", name: "Panama"},
        {code: "PG", name: "Papua New Guinea"},
        {code: "PY", name: "Paraguay"},
        {code: "PE", name: "Peru"},
        {code: "PH", name: "Philippines"},
        {code: "PN", name: "Pitcairn"},
        {code: "PL", name: "Poland"},
        {code: "PT", name: "Portugal"},
        {code: "PR", name: "Puerto Rico"},
        {code: "QA", name: "Qatar"},
        {code: "RE", name: "Réunion"},
        {code: "RO", name: "Romania"},
        {code: "RU", name: "Russian Federation"},
        {code: "RW", name: "Rwanda"},
        {code: "BL", name: "Saint Barthélemy"},
        {code: "SH", name: "Saint Helena"},
        {code: "KN", name: "Saint Kitts and Nevis"},
        {code: "LC", name: "Saint Lucia"},
        {code: "MF", name: "Saint Martin"},
        {code: "PM", name: "Saint Pierre and Miquelon"},
        {code: "VC", name: "Saint Vincent and the Grenadines"},
        {code: "WS", name: "Samoa"},
        {code: "SM", name: "San Marino"},
        {code: "ST", name: "Sao Tome and Principe"},
        {code: "SA", name: "Saudi Arabia"},
        {code: "SN", name: "Senegal"},
        {code: "RS", name: "Serbia"},
        {code: "SC", name: "Seychelles"},
        {code: "SL", name: "Sierra Leone"},
        {code: "SG", name: "Singapore"},
        {code: "SK", name: "Slovakia"},
        {code: "SI", name: "Slovenia"},
        {code: "SB", name: "Solomon Islands"},
        {code: "SO", name: "Somalia"},
        {code: "ZA", name: "South Africa"},
        {code: "GS", name: "South Georgia and the South Sandwich Islands"},
        {code: "ES", name: "Spain"},
        {code: "LK", name: "Sri Lanka"},
        {code: "SD", name: "Sudan"},
        {code: "SR", name: "Suriname"},
        {code: "SJ", name: "Svalbard and Jan Mayen"},
        {code: "SZ", name: "Swaziland"},
        {code: "SE", name: "Sweden"},
        {code: "CH", name: "Switzerland"},
        {code: "SY", name: "Syrian Arab Republic"},
        {code: "TW", name: "Taiwan, Province of China"},
        {code: "TJ", name: "Tajikistan"},
        {code: "TZ", name: "Tanzania, United Republic of"},
        {code: "TH", name: "Thailand"},
        {code: "TL", name: "Timor-Leste"},
        {code: "TG", name: "Togo"},
        {code: "TK", name: "Tokelau"},
        {code: "TO", name: "Tonga"},
        {code: "TT", name: "Trinidad and Tobago"},
        {code: "TN", name: "Tunisia"},
        {code: "TR", name: "Turkey"},
        {code: "TM", name: "Turkmenistan"},
        {code: "TC", name: "Turks and Caicos Islands"},
        {code: "TV", name: "Tuvalu"},
        {code: "UG", name: "Uganda"},
        {code: "UA", name: "Ukraine"},
        {code: "AE", name: "United Arab Emirates"},
        {code: "GB", name: "United Kingdom"},
        {code: "US", name: "United States"},
        {code: "UM", name: "United States Minor Outlying Islands"},
        {code: "UY", name: "Uruguay"},
        {code: "UZ", name: "Uzbekistan"},
        {code: "VU", name: "Vanuatu"},
        {code: "VE", name: "Venezuela"},
        {code: "VN", name: "Viet Nam"},
        {code: "VG", name: "Virgin Islands, British"},
        {code: "VI", name: "Virgin Islands, U.S."},
        {code: "WF", name: "Wallis and Futuna"},
        {code: "EH", name: "Western Sahara"},
        {code: "YE", name: "Yemen"},
        {code: "ZM", name: "Zambia"},
        {code: "ZW", name: "Zimbabwe"}
        ];
        <?php
    }
}
