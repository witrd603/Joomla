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

namespace ThePhpFactory\LoveFactory\Restrictions\Type;

defined('_JEXEC') or die;

use ThePhpFactory\LoveFactory\Restrictions\BooleanRestriction;
use ThePhpFactory\LoveFactory\Restrictions\Restriction;

class SameGenderInteraction extends Restriction implements BooleanRestriction
{
    protected $restrictionName = 'same_gender_interaction';
    protected $restrictionMessage = 'membership_restriction_error_same_gender_interaction';

    public function isAllowed($senderUserId, $receiverUserId)
    {
        if ($senderUserId == $receiverUserId) {
            return true;
        }

        $restriction = $this->getCurrentMembershipRestriction($senderUserId);

        if ($restriction) {
            return true;
        }

        $senderGender = $this->getGender($senderUserId);
        $receiverGender = $this->getGender($receiverUserId);

        if ($senderGender == $receiverGender) {
            throw new \Exception(\FactoryText::_($this->getRestrictionMessage()));
        }

        return true;
    }

    protected function getGender($userId)
    {
        $profile = \JTable::getInstance('Profile', 'Table');
        $profile->load($userId);

        return $profile->sex;
    }
}
