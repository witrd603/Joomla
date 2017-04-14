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

class Messages extends CountableRestriction implements CountableUnlimited
{
    protected $restrictionName = 'messages';
    protected $restrictionMessage = 'membership_restriction_error_messages_limit';

    protected function count($userId)
    {
        $table = \JTable::getInstance('StatisticsPerDay', 'Table');
        $date = \JFactory::getDate();

        $query = $this->dbo->getQuery(true)
            ->select('s.messages')
            ->from($this->dbo->qn($table->getTableName(), 's'))
            ->where('s.user_id = ' . $this->dbo->q($userId))
            ->where('s.date_messages = ' . $this->dbo->q($date->format('Y-m-d')));

        $result = $this->dbo->setQuery($query)
            ->loadObject();

        if (!$result) {
            return 0;
        }

        return $result->messages;
    }
}
