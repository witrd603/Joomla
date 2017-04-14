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

namespace ThePhpFactory\LoveFactory\Helper;

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

class OppositeGender
{
    protected $settings;

    public function __construct(\LovefactorySettings $settings)
    {
        $this->settings = $settings;
    }

    public function isOppositeGenderSearchEnabled(\JUser $user)
    {
        if ($user->guest) {
            return false;
        }

        if ($this->settings->opposite_gender_search) {
            return true;
        }

        return false;
    }

    public function isOppositeGenderDisplayEnabled(\JUser $user)
    {
        if ($user->guest) {
            return false;
        }

        if ($this->settings->opposite_gender_display) {
            return true;
        }

        return false;
    }

    public function addOppositeGenderSearchCondition(\JDatabaseQuery $query, \JUser $user, $tableAlias = 'p')
    {
        if (!$user || $user->guest) {
            return false;
        }

        $userTable = \JTable::getInstance('Profile', 'Table');
        $fieldTable = \JTable::getInstance('Field', 'Table');

        if (!$userTable->load($user->id)) {
            return false;
        }

        if (!$fieldTable->load(array('type' => 'Gender'))) {
            return false;
        }

        $registry = new Registry($fieldTable->params);
        $params = $registry->toArray();

        if (!isset($params['opposite'][$userTable->sex])) {
            return false;
        }

        $oppositeGender = $params['opposite'][$userTable->sex];

        // Add search conditions
        $query->where($tableAlias . '.sex = ' . $query->q($oppositeGender));

        return true;
    }
}
