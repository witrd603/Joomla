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

class TableFriend extends JTable
{
    var $id = null;
    var $type = null;
    var $receiver_id = null;
    var $sender_id = null;
    var $date = null;
    var $message = null;
    var $sender_status = null;
    var $receiver_status = null;
    var $pending = null;

    public function __construct(&$db)
    {
        parent::__construct('#__lovefactory_friends', 'id', $db);
    }

    public function request($sender_id, $receiver_id, $message, $type = 1)
    {
        $date = JFactory::getDate();

        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->date = $date->toSql();
        $this->message = $message;
        $this->pending = 1;
        $this->type = $type;

        if (!$this->store()) {
            return false;
        }

        $this->storeRequest($this->sender_id, $this->receiver_id);

        return true;
    }

    public function accept()
    {
        $this->pending = 0;
        $this->store();

        $sender = JTable::getInstance('profile', 'Table');
        $receiver = JTable::getInstance('profile', 'Table');

        $sender->load($this->sender_id);
        $receiver->load($this->receiver_id);

        switch ($this->type) {
            // Friendship
//	    case 1:
//	      $sender->friends++;
//	      $receiver->friends++;
//	    break;

            // Relationship
            case 2:
                $sender->relationship = $this->receiver_id;
                $receiver->relationship = $this->sender_id;
                break;
        }

        $sender->store();
        $receiver->store();

        // Register activity.
//    $mode = 1 == $this->type ? 'friend' : 'relationship';
//    $activity = JTable::getInstance('Activity', 'Table');
//    $activity->register($mode . '_add', $this->sender_id, $this->receiver_id);

        $mode = 1 == $this->type ? 'friend' : 'relationship';

        JEventDispatcher::getInstance()->trigger('onLoveFactoryFriendshipAccepted', array(
            'com_lovefactory.friendship_accepted',
            $this,
            $mode
        ));
    }

    public function remove()
    {
        $sender = JTable::getInstance('profile', 'Table');
        $receiver = JTable::getInstance('profile', 'Table');

        $sender->load($this->sender_id);
        $receiver->load($this->receiver_id);

        switch ($this->type) {
            // Friendship
//      case 1:
//        $sender->friends--;
//        $receiver->friends--;
//        break;

            // Relationship
            case 2:
                $sender->relationship = 0;
                $receiver->relationship = 0;
                break;
        }

        $sender->store();
        $receiver->store();

        if (!$this->delete()) {
            return false;
        }

        $mode = 1 == $this->type ? 'friend' : 'relationship';

        JEventDispatcher::getInstance()->trigger('onLoveFactoryFriendshipRemoved', array(
            'com_lovefactory.friendship_removed',
            $this,
            $mode
        ));

        return true;
    }

    public function approve()
    {
        $this->approved = 1;

        if (!$this->store()) {
            return false;
        }

        return true;
    }

    public function reject()
    {
        $this->approved = 1;
        $this->message = '';

        if (!$this->store()) {
            return false;
        }

        return true;
    }

    protected function storeRequest($senderId, $receiverId)
    {
        if (!LoveFactoryApplication::getInstance()->getSettings('friendship_requests_limit')) {
            return true;
        }

        $dbo = $this->getDbo();
        $date = JFactory::getDate();

        $query = $dbo->getQuery(true)
            ->insert('#__lovefactory_friends_requests')
            ->set('sender_id = ' . $dbo->quote($senderId))
            ->set('receiver_id = ' . $dbo->quote($receiverId))
            ->set('created_at = ' . $dbo->quote($date->toSql()));

        return $dbo->setQuery($query)
            ->execute();
    }
}
