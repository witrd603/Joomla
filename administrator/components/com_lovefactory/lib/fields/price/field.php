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

class LoveFactoryFieldPrice extends LoveFactoryField
{
    protected $accessPageWhiteList = array('registration');
    protected $sex = null;

    public function renderInputEdit()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        if ($settings->gender_pricing) {
            FactoryHtml::script('fields/price');
            $this->addHelpText(FactoryText::_('field_price_gender_pricing_help'));
        }

        /** @var FrontendModelMembershipBuy $module */
        $module = JModelLegacy::getInstance('MembershipBuy', 'FrontendModel');
        $url = JRoute::_(JUri::root() . 'index.php?option=com_lovefactory&controller=signup&task=priceupdate');

        $html = array();
        $html[] = '<div data-type="price" data-url="' . $url . '">';
        $html[] = $module->getPriceSelect('form[gateway]', $this->data, $this->sex);
        $html[] = '</div>';

        return implode("\n", $html);
    }

    public function bind($data)
    {
        if (isset($data['sex'])) {
            $this->sex = $data['sex'];
        }

        return parent::bind($data);
    }

    public function validate()
    {
        if (!parent::validate()) {
            return false;
        }

        $price_id = $this->data;
        $price = JTable::getInstance('Price', 'Table');
        $membership = JTable::getInstance('Membership', 'Table');
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        $data = JFactory::getApplication()->input->get('form', array(), 'array');

        // Load price
        if ((!empty($price_id) && !$price->load($price_id)) || empty($price_id)) {
            $this->setError(FactoryText::_('gateway_process_error_price_not_found'));
            return false;
        }

        // Check if price is available
        if (!$price->published) {
            $this->setError(FactoryText::_('gateway_process_error_price_not_found'));
            return false;
        }

        // Check if gender price is available
        if ($settings->gender_pricing && !$price->hasGender($data['sex'])) {
            $this->setError(FactoryText::_('gateway_process_error_price_not_available'));
            return false;
        }

        // Load membership
        if ((!empty($price->membership_id) && !$membership->load($price->membership_id)) || empty($price->membership_id)) {
            $this->setError(FactoryText::_('gateway_process_error_membership_not_found'));
            return false;
        }

        // Check if membership is available
        if (!$membership->published) {
            $this->setError(FactoryText::_('gateway_process_error_membership_not_found'));
            return false;
        }

        return true;
    }

    public function getId()
    {
        return 'gateway';
    }
}
