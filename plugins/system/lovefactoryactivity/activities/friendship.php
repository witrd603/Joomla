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

class ActivityFriendship extends LoveFactoryActivity
{
    protected $event = null;

    public function register(TableActivity $activity, array $params = array())
    {
        /** @var TableFriend $friendship */
        $friendship = $params['friendship'];
        $friendshipType = $params['friendshipType'];

        return $activity->register(
            $friendshipType . '_add',
            $friendship->sender_id,
            $friendship->receiver_id,
            $friendship->id
        );
    }

    public function remove(TableActivity $activity, $itemId, array $params = array())
    {
        $friendship = JTable::getInstance('Friend', 'Table');
        $friendship->load($itemId);

        switch ($friendship->type) {
            case 1:
                $this->event = 'friend_add';
                break;

            case 2:
                $this->event = 'relationship_add';
                break;
        }

        return parent::remove($activity, $itemId);
    }
}
