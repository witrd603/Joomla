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

class FrontendModelMemberships extends FactoryModel
{
    private $hiddenFeatures = array();

    public function getItems()
    {
        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('m.*')
            ->from('#__lovefactory_memberships m')
            ->where('m.published = ' . $dbo->quote(1))
            ->order('m.ordering ASC');

        $results = $dbo->setQuery($query)
            ->loadObjectList();

        $results = $this->prepare($results);

        return $results;
    }

    public function getFeatures()
    {
        $features = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::getTypes();
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        $features = array_flip($features);

        if (!$settings->enable_chatfactory_integration) {
            unset($features['chat_factory_access']);
        }

        if (!$settings->enable_blogfactory_integration) {
            unset($features['blog_factory_access']);
        }

        if (!$settings->enable_groups) {
            unset($features['groups_create'], $features['groups_join']);
        }

        if (!$settings->groups_allow_users_create) {
            unset($features['groups_create']);
        }

        if (!$settings->enable_shoutbox) {
            unset($features['shoutbox']);
        }

        if (!$settings->enable_top_friends) {
            unset($features['friends_top']);
        }

        if (!$settings->enable_friends) {
            unset($features['friends']);
            unset($features['friends_top']);
        }

        if (!$settings->enable_interactions) {
            unset($features['interactions']);
        }

        foreach ($this->hiddenFeatures as $feature) {
            unset($features[$feature]);
        }

        return $features;
    }

    public function getPrices()
    {
        /** @var TablePrice $result */
        $results = $this->getPricesRaw();

        $defaultMembership = JTable::getInstance('Membership', 'Table');
        $defaultMembership->loadDefault();

        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $genders = $this->getAvailableGenders();
        $months = array();

        $profile = JTable::getInstance('Profile', 'Table');
        $profile->load(JFactory::getUser()->id);

        foreach ($results as $result) {
            if ($result->membership_id == $defaultMembership->id) {
                continue;
            }

            if (!isset($months[$result->months])) {
                $months[$result->months] = array();
            }

            if (!$settings->gender_pricing) {
                $months[$result->months][$result->membership_id] = $result;
                $result->amount = $result->price;

                if ('0.00' != $result->price) {
                    $result->price = JHtml::_('LoveFactory.currency', $result->price, $settings->currency);
                } else {
                    $result->price = FactoryText::_('memberships_price_free');
                }
            } else {
                $array = array();

                foreach (explode("\n", $result->gender_prices) as $price) {
                    if (false === strpos($price, '=')) {
                        continue;
                    }

                    list ($gender, $price) = explode('=', $price);

                    // Check if price is available.
                    if (-1 == $price) {
                        continue;
                    }

                    if (!$profile->user_id || ($profile->user_id && $profile->sex == $gender)) {
                        if (0.00 != $price) {
                            $temp = JHtml::_('LoveFactory.currency', $price, $settings->currency);
                        } else {
                            $temp = FactoryText::_('memberships_price_free');
                        }

                        $array[] = array(
                            'label' => $temp . '<small class="gender">[' . $genders[$gender] . ']</small>',
                            'price' => $price,
                        );
                    }
                }

                if ($array) {
                    $months[$result->months][$result->membership_id][$result->id] = $array;
                }
            }

            if (!$months[$result->months]) {
                unset($months[$result->months]);
            }
        }

        function cmp($a, $b)
        {
            if (0 == $a) {
                return true;
            }

            if (0 == $b) {
                return false;
            }

            return $a > $b;
        }

        uksort($months, 'cmp');

        return $months;
    }

    public function getTrials()
    {
        $membership = JTable::getInstance('Membership', 'Table');
        $membership->loadDefault();

        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('p.*')
            ->from('#__lovefactory_pricing p')
            ->where('p.published = ' . $dbo->quote(1))
            ->where('p.is_trial = ' . $dbo->quote(1))
            ->where('p.membership_id <> ' . $dbo->q($membership->id))
            ->where('(CASE WHEN p.available_from <> ' . $dbo->quote($dbo->getNullDate()) . ' THEN p.available_from <= ' . $dbo->quote(JFactory::getDate()->toSql()) . ' ELSE true END)')
            ->where('(CASE WHEN p.available_until <> ' . $dbo->quote($dbo->getNullDate()) . ' THEN p.available_until >= ' . $dbo->quote(JFactory::getDate()->toSql()) . ' ELSE true END)');

        $results = $dbo->setQuery($query)
            ->loadObjectList();

        $array = array();
        foreach ($results as $result) {
            if (!isset($array[$result->months])) {
                $array[$result->months] = array();
            }

            $array[$result->months][$result->membership_id] = $result;
        }

        return $array;
    }

    public function getAvailableGenders()
    {
        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_lovefactory/models');
        $model = JModelLegacy::getInstance('Price', 'BackendModel');

        return $model->getGenders();
    }

    public function prepare($results)
    {
        if (!is_array($results)) {
            $results = array($results);
        }

        $availableFeatures = $this->getFeatures();

        foreach ($results as $result) {
            $restrictions = new \Joomla\Registry\Registry($result->restrictions);
            $features = array();

            foreach ($restrictions->toArray() as $restriction => $value) {
                if (!isset($availableFeatures[$restriction])) {
                    continue;
                }

                $features[$restriction] = $this->getRestriction($restriction)->renderDisplay($value);
            }

            $result->features = $features;
        }

        return $results;
    }

    public function getPricesRaw()
    {
        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('p.*, m.title')
            ->from('#__lovefactory_pricing p')
            ->leftJoin('#__lovefactory_memberships m ON m.id = p.membership_id')
            ->where('p.published = ' . $dbo->quote(1))
            ->where('p.is_trial = ' . $dbo->quote(0));

        JLoader::register('TablePrice', JPATH_COMPONENT_ADMINISTRATOR . DS . 'tables' . DS . 'price.php');
        $results = $dbo->setQuery($query)
            ->loadObjectList();

        $array = array();
        foreach ($results as $result) {
            $table = JTable::getInstance('Price', 'Table');
            $table->bind($result);
            $table->title = $result->title;

            $array[$result->id] = $table;
        }

        return $array;
    }

    private function getRestriction($name)
    {
        static $restrictions = array();

        if (!isset($restrictions[$name])) {
            $restrictions[$name] = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction($name);
        }

        return $restrictions[$name];
    }
}
