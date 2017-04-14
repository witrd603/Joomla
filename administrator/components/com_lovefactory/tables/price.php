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

class TablePrice extends JTable
{
    const UNLIMITED_EXPIRATION = null;
    const INTERVAL_UNIT_REGULAR = 'months';
    const INTERVAL_UNIT_TRIAL = 'hours';

    public $months;
    public $is_trial;

    public function __construct(&$db = null)
    {
        if (is_null($db)) {
            $db = JFactory::getDbo();
        }

        parent::__construct('#__lovefactory_pricing', 'id', $db);
    }

    public function hasGender($gender)
    {
        $genders = $this->getGenders();

        return -1 != @$genders[$gender];
    }

    public function getGenders()
    {
        $exploded = explode("\n", $this->gender_prices);

        $array = array();
        foreach ($exploded as $gender) {
            $price = explode('=', $gender);

            if (2 == count($price)) {
                $array[$price[0]] = $price[1];
            }
        }

        return $array;
    }

    public function getGenderPrice($gender = null)
    {
        if (is_null($gender)) {
            $profile = JTable::getInstance('Profile', 'Table');
            $profile->load(JFactory::getUser()->id);
            $gender = $profile->sex;
        }

        $genders = $this->getGenders();

        return isset($genders[$gender]) ? $genders[$gender] : -1;
    }

    public function bind($from, $ignore = array())
    {
        if (!parent::bind($from)) {
            return false;
        }

        if ($this->is_trial) {
            $this->price = 0;
            $this->gender_prices = '';
        }

        return true;
    }

    public function getPrice($gender = null)
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if (!$settings->gender_pricing) {
            $value = $this->price;
        } else {
            $value = $this->getGenderPrice($gender);
        }

        return $value;
    }

    public function isUnlimited()
    {
        return 0 == $this->months;
    }

    public function calculateExpirationDate($from = null)
    {
        if ($this->isUnlimited()) {
            return self::UNLIMITED_EXPIRATION;
        }

        $unit = $this->getIntervalUnit();
        $shift = '+' . $this->months . ' ' . $unit;

        if (null === $from) {
            return JFactory::getDate($shift);
        }

        return JFactory::getDate($from . ' ' . $shift);
    }

    public function isTrial()
    {
        return 1 === (int)$this->is_trial;
    }

    private function getIntervalUnit()
    {
        if ($this->isTrial()) {
            return self::INTERVAL_UNIT_TRIAL;
        }

        return self::INTERVAL_UNIT_REGULAR;
    }
}
