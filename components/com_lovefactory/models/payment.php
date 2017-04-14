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

jimport('joomla.application.component.model');

JLoader::register('FactoryModel', JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/methods.php');

class FrontendModelPayment extends FactoryModel
{
    public function notify($gateway_id)
    {
        $gateway = $this->getGateway($gateway_id);

        if (!$gateway) {
            return false;
        }

        $language = JFactory::getLanguage();
        $language->load('com_lovefactory');
        $language->load($gateway->get('element'), JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'payment');

        $gateway->processIpn();

        return true;
    }

    public function getGatewayError()
    {
        $sesion = JFactory::getSession();
        $error = $sesion->get('com_lovefactory.payment.error');

        $sesion->set('com_lovefactory.payment.error', null);

        return $error;
    }

    protected function getGateway($gateway_id)
    {
        $gateway = $this->getTable('Gateway', 'Table');

        // Check if gateway exists
        if (!$gateway_id || !$gateway->load($gateway_id)) {
            $this->setError(JText::_('COM_LOVEFACTORY_PAYMENT_GATEWAY_NOT_FOUND'));
            return false;
        }

        // Check if gateway is published
        if (!$gateway->published) {
            $this->setError(JText::_('COM_LOVEFACTORY_PAYMENT_GATEWAY_NOT_FOUND'));
            return false;
        }

        // Check if payment plugin file exists
        jimport('joomla.filesystem.file');
        $path = JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'payment' . DS . 'gateways' . DS . $gateway->element . DS . $gateway->element . '.php';

        if (!JFile::exists($path)) {
            $this->setError(JText::_('COM_LOVEFACTORY_PAYMENT_GATEWAY_NOT_FOUND!'));
            return false;
        }

        JLoader::register($gateway->element, $path);

        $gateway = new $gateway->element(array(
            'gateway' => $gateway,
        ));

        return $gateway;
    }
}
