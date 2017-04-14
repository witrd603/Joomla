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

namespace ThePhpFactory\LoveFactory\Restrictions;

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

abstract class Restriction
{
    protected $dbo;
    protected $restrictionName;
    protected $restrictionMessage;

    public function __construct(\JDatabaseDriver $dbo)
    {
        $this->dbo = $dbo;
    }

//  abstract public function isAllowed($userId);

    public function getRestrictionName()
    {
        return $this->restrictionName;
    }

    public function renderDisplay($value)
    {
        if ($this instanceof CountableUnlimited && -1 === (integer)$value) {
            return \FactoryText::_('membership_restriction_unlimited');
        }

        if ($this instanceof BooleanRestriction) {
            return \FactoryText::plural('membership_restriction_boolean', $value);
        }

        return $value;
    }

    public function getCurrentMembershipRestriction($userId)
    {
        static $restrictions = array();

        if (!isset($restriction[$userId])) {
            $membership = \JTable::getInstance('MembershipSold', 'Table');
            $data = array(
                'user_id' => $userId,
                'expired' => 0,
            );

            if (!$membership->load($data)) {
                $membership = \JTable::getInstance('Membership', 'Table');
                $membership->load(array(
                    'default' => 1
                ));
            }

            $restrictions[$userId] = new Registry($membership->restrictions);
        }

        return $restrictions[$userId]->get($this->getRestrictionName());
    }

    protected function getRestrictionMessage()
    {
        return $this->restrictionMessage;
    }
}
