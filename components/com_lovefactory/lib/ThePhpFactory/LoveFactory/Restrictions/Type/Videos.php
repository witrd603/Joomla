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

class Videos extends CountableRestriction implements CountableUnlimited, AdjustableRestriction
{
    protected $restrictionName = 'videos';
    protected $restrictionMessage = 'profile_membership_videos_restriction_reached';

    public function adjustResources($restriction, $userId)
    {
        // Check if new restriction is unlimited.
        if (-1 == $restriction) {
            return true;
        }

        $videos = $this->count($userId);

        // Check if user has more videos than the new restriction.
        if ($videos <= $restriction) {
            return true;
        }

        $table = \JTable::getInstance('Video', 'TableLoveFactory');

        // Get photos that are public or for friends.
        $query = $this->dbo->getQuery(true)
            ->select('v.id')
            ->from($this->dbo->qn($table->getTableName(), 'v'))
            ->where('v.status IN (' . implode(',', $this->dbo->q(array(0, 1))) . ')')
            ->where('v.user_id = ' . $this->dbo->q($userId))
            ->order('v.date_added DESC');
        $results = $this->dbo->setQuery($query)
            ->loadAssocList('id');

        $public = count($results);

        if ($public <= $restriction) {
            return true;
        }

        $results = array_slice(array_keys($results), 0, $public - $restriction);

        // Set videos privacy to private.
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
        $table = \JTable::getInstance('LoveFactoryVideo', 'Table');

        $query = $this->dbo->getQuery(true)
            ->select('COUNT(v.id)')
            ->from($this->dbo->quoteName($table->getTableName()) . ' v')
            ->where('v.user_id = ' . $this->dbo->quote($userId));

        $result = $this->dbo->setQuery($query)
            ->loadResult();

        return $result;
    }
}
