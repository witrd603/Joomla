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

use ThePhpFactory\LoveFactory\Restrictions\AdjustableRestriction;
use ThePhpFactory\LoveFactory\Restrictions\CountableRestriction;
use ThePhpFactory\LoveFactory\Restrictions\CountableUnlimited;

class Photos extends CountableRestriction implements CountableUnlimited, AdjustableRestriction
{
    protected $restrictionName = 'photos';
    protected $restrictionMessage = 'profile_membership_photos_restriction_reached';

    public function adjustResources($restriction, $userId)
    {
        // Check if new restriction is unlimited.
        if (-1 == $restriction) {
            return true;
        }

        $photos = $this->count($userId);

        // Check if user has more photos than the new restriction.
        if ($photos <= $restriction) {
            return true;
        }

        $table = \JTable::getInstance('Photo', 'Table');

        // Get photos that are public or for friends.
        $query = $this->dbo->getQuery(true)
            ->select('p.id')
            ->from($this->dbo->qn($table->getTableName(), 'p'))
            ->where('p.status IN (' . implode(',', $this->dbo->q(array(0, 1))) . ')')
            ->where('p.user_id = ' . $this->dbo->q($userId))
            ->order('p.date_added DESC');
        $results = $this->dbo->setQuery($query)
            ->loadAssocList('id');

        $public = count($results);

        if ($public <= $restriction) {
            return true;
        }

        $results = array_slice(array_keys($results), 0, $public - $restriction);

        // Set photos privacy to private.
        if ($results) {
            $query = $this->dbo->getQuery(true)
                ->update($this->dbo->qn($table->getTableName()))
                ->set('status = ' . $this->dbo->q(2))
                ->where('id IN (' . implode(',', $this->dbo->q($results)) . ')')
                ->where('user_id = ' . $this->dbo->q($userId));

            $this->dbo->setQuery($query)
                ->execute();
        }

        return true;
    }

    protected function count($userId)
    {
        $table = \JTable::getInstance('Photo', 'Table');

        $query = $this->dbo->getQuery(true)
            ->select('COUNT(p.id)')
            ->from($this->dbo->quoteName($table->getTableName()) . ' p')
            ->where('p.user_id = ' . $this->dbo->quote($userId));

        $result = $this->dbo->setQuery($query)
            ->loadResult();

        return $result;
    }
}
