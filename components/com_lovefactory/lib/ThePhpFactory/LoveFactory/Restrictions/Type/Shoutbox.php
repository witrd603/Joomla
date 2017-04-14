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

use ThePhpFactory\LoveFactory\Restrictions\ListRestriction;
use ThePhpFactory\LoveFactory\Restrictions\Restriction;

class Shoutbox extends Restriction implements ListRestriction
{
    protected $restrictionName = 'shoutbox';

    public function isAllowed($userId)
    {
        $restriction = $this->getCurrentMembershipRestriction($userId);

        return 0 < $restriction;
    }

    public function hasFullAccess($userId)
    {
        $restriction = $this->getCurrentMembershipRestriction($userId);

        return 2 == $restriction;
    }

    public function getListValues()
    {
        return array(
            0 => \FactoryText::_('restriction_shoutbox_access_no_access'),
            1 => \FactoryText::_('restriction_shoutbox_access_read_access'),
            2 => \FactoryText::_('restriction_shoutbox_access_full_access'),
        );
    }

    public function renderDisplay($value)
    {
        $values = $this->getListValues();

        return $values[$value];
    }
}
