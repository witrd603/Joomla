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

class LoveFactoryFieldGateway extends LoveFactoryField
{
    protected $accessPageWhiteList = array('registration');

    public function renderInputEdit()
    {
        /** @var FrontendModelMembershipBuy $module */
        $module = JModelLegacy::getInstance('MembershipBuy', 'FrontendModel');
        $gateways = $module->getGateways();

        if (1 == count($gateways)) {
            $this->data = reset($gateways)->getId();
        }

        $html = array();

        $html[] = '<table>';
        foreach ($gateways as $gateway) {
            $checked = $this->data == $gateway->getId() ? 'checked="checked"' : '';

            $html[] = '<tr>';
            $html[] = '<td><input type="radio" ' . $checked . ' name="form[method]" value="' . $gateway->getId() . '" id="form_method_' . $gateway->getId() . '" /></td>';
            $html[] = '<td><label for="form_method_' . $gateway->getId() . '"><img src="' . $gateway->getLogo() . '" alt="' . $gateway->getTitle() . '" style="cursor: pointer;" /></label></td>';
            $html[] = '</tr>';
        }
        $html[] = '</table>';

        return implode("\n", $html);
    }

    public function validate()
    {
        if (!parent::validate()) {
            return false;
        }

        $gateway_id = $this->data;
        $gateway = JTable::getInstance('Gateway', 'Table');

        // Find gateway
        $result = $gateway->find(array('id' => $gateway_id));
        if (!$result) {
            $this->setError(FactoryText::_('gateway_process_error_not_found'));
            return false;
        }

        $gateway->load($result);

        // Check if gateway is enabled
        if (!$gateway->published) {
            $this->setError(FactoryText::_('gateway_process_error_not_found'));
            return false;
        }

        return true;
    }

    public function getId()
    {
        return 'method';
    }
}
