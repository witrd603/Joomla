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

class FrontendModelMembershipBuy extends FactoryModel
{
    public function getPriceSelect($name = 'price', $selected = null, $sex = null)
    {
        /** @var FrontendModelMemberships $model */

        JLoader::register('JHtmlLoveFactory', JPATH_SITE . '/components/com_lovefactory/lib/html/html.php');

        // Initialise variables.
        if (null === $selected) {
            $selected = JFactory::getApplication()->input->getInt('id', 0);
        }

        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $model = JModelLegacy::getInstance('Memberships', 'FrontendModel');
        $prices = $model->getPricesRaw();
        $options = array();

        if (null === $sex) {
            $profile = $this->getTable('Profile');
            $profile->load(JFactory::getUser()->id);

            $sex = $profile->sex;
        }

        if ($selected && !isset($prices[$selected])) {
            //JFactory::getApplication()->enqueueMessage(FactoryText::_('membershipbuy_selected_price_notfound'), 'notice');
        }

        $genderString = '';

        if ($settings->gender_pricing) {
            $genders = $model->getAvailableGenders();
            $genderString = isset($genders[$sex]) ? $genders[$sex] : '';
        }

        foreach ($prices as $price) {
            $amount = $price->getPrice($sex);

            if (in_array($amount, array(-1, 0))) {
                continue;
            }

            $amount = JHtml::_('LoveFactory.currency', $amount, $settings->currency);

            $text = FactoryText::plural('order_create_title', $price->months, $price->title, $genderString, $amount, $settings->currency);
            $options[] = JHTML::_('select.option', $price->id, $text);
        }

        $output = JHTML::_('select.genericlist', $options, $name, '', 'value', 'text', $selected);

        return $output;
    }

    public function getGateways()
    {
        $dbo = $this->getDbo();
        $gateways = array();

        $query = $dbo->getQuery(true)
            ->select('g.*')
            ->from('#__lovefactory_gateways g')
            ->where('g.published = 1')
            ->order('g.ordering ASC');

        $results = $dbo->setQuery($query)
            ->loadObjectList();

        foreach ($results as $result) {
            JLoader::register($result->element, JPATH_ADMINISTRATOR . '/components/com_lovefactory/payment/gateways/' . $result->element . '/' . $result->element . '.php');

            if (class_exists($result->element)) {
                $gateway = new $result->element(array(
                    'gateway' => $result
                ));

                $gateways[] = $gateway;
            }
        }

        return $gateways;
    }
}
