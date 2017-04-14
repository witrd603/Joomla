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

class ActivityGroupMember extends LoveFactoryActivity
{
    protected $event = 'group_join';

    public function register(TableActivity $activity, array $params = array())
    {
        $member = $params['member'];
        $group = $params['group'];

        return $activity->register(
            $this->event,
            $member->user_id,
            $member->user_id,
            $member->group_id,
            array(
                'title' => $group->title,
            ),
            $member->created_at
        );
    }

    public function remove(TableActivity $activity, $itemId, array $params = array())
    {
        $dbo = JFactory::getDbo();
        $member = $params['member'];

        $query = $dbo->getQuery(true)
            ->select('a.id')
            ->from($dbo->qn($activity->getTableName(), 'a'))
            ->where('event = ' . $dbo->q($this->event))
            ->where('item_id = ' . $dbo->q($member->group_id))
            ->where('sender_id = ' . $dbo->q($member->user_id));

        $results = $dbo->setQuery($query)
            ->loadAssocList();

        foreach ($results as $result) {
            $activity->delete($result['id']);
        }

        return true;
    }
}
