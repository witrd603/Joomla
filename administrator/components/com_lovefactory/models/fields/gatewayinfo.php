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

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldGatewayinfo extends JFormField
{
    public $type = 'GatewayInfo';

    protected function getInput()
    {
        // Initialise variables
        $html = JText::_($this->description);
        $id = $this->form->getValue('id');

        // Replace Notification Url
        $html = str_replace('%%url.notification%%', $this->getUrlNotification($id), $html);

        return JText::_($html);
    }

    protected function getUrlNotification($gateway_id)
    {
        $gateway = $this->getGateway($gateway_id);

        return $gateway->get('url.notification');
    }

    protected function getGateway($gateway_id)
    {
        static $gateways = array();

        if (!isset($gateways[$gateway_id])) {
            $gateway = JTable::getInstance('Gateway', 'Table');
            $gateway->load($gateway_id);

            jimport('joomla.filesystem.file');
            $path = JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'payment' . DS . 'gateways' . DS . $gateway->element . DS . $gateway->element . '.php';

            if (!JFile::exists($path)) {
                return false;
            }

            JLoader::register($gateway->element, $path);

            $gateways[$gateway_id] = new $gateway->element(array(
                'gateway' => $gateway,
            ));
        }

        return $gateways[$gateway_id];
    }
}
