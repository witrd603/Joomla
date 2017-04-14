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

use ThePhpFactory\LoveFactory\Restrictions\CountableRestriction;
use ThePhpFactory\LoveFactory\Restrictions\CountableUnlimited;

class GroupsCreate extends CountableRestriction implements CountableUnlimited
{
    protected $restrictionName = 'groups_create';
    protected $restrictionMessage = 'membership_restriction_error_create_group';

    protected function count($userId)
    {
        $table = \JTable::getInstance('Group', 'Table');

        $query = $this->dbo->getQuery(true)
            ->select('COUNT(1)')
            ->from($this->dbo->qn($table->getTableName(), 'g'))
            ->where('g.user_id = ' . $this->dbo->quote($userId));

        $result = $this->dbo->setQuery($query)
            ->loadResult();

        return $result;
    }
}
