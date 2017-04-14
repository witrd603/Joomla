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

class Friends extends CountableRestriction implements CountableUnlimited
{
    protected $restrictionName = 'friends';
    protected $restrictionMessage = 'membership_restriction_error_friends_limit';

    protected function count($userId)
    {
        $table = \JTable::getInstance('Friend', 'Table');

        $query = $this->dbo->getQuery(true)
            ->select('COUNT(f.id)')
            ->from($this->dbo->qn($table->getTableName(), 'f'))
            ->where('(f.sender_id = ' . $this->dbo->quote($userId) . ' OR (f.receiver_id = ' . $this->dbo->quote($userId) . ' AND f.pending = ' . $this->dbo->q(0) . '))')
            ->where('f.type = ' . $this->dbo->quote(1));

        $result = $this->dbo->setQuery($query)
            ->loadResult();

        return $result;
    }
}
