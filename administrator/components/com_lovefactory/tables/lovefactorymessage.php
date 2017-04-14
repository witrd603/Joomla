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

class TableLoveFactoryMessage extends JTable
{
    var $id = null;
    var $receiver_id = null;
    var $sender_id = null;
    var $date = null;
    var $title = null;
    var $text = null;
    var $unread = null;
    var $reported = null;
    var $deleted_by_sender = null;
    var $deleted_by_receiver = null;
    var $approved = null;

    public function __construct(&$db)
    {
        parent::__construct('#__lovefactory_messages', 'id', $db);
    }

    public function send($to, $from, $title, $text)
    {
        $date = JFactory::getDate();

        $this->sender_id = $from;
        $this->receiver_id = $to;
        $this->title = $title;
        $this->text = $text;
        $this->unread = 1;
        $this->date = $date->toSql();

        if (!$this->store()) {
            return false;
        }

        JEventDispatcher::getInstance()->trigger('onLoveFactoryMessageSent', array(
            'com_lovefactory.message_sent',
            $this
        ));

        return true;
    }

    public function sendSystemMessage($to, $title, $text)
    {
        $this->deleted_by_sender = 1;
        $this->approved = 1;
        $this->send($to, 0, $title, $text);
    }

    public function markAsRead()
    {
        $this->unread = 0;

        $this->store();
    }

    public function getReceiverName()
    {
        $query = ' SELECT username'
            . ' FROM #__users'
            . ' WHERE id = ' . $this->receiver_id;
        $this->_db->setQuery($query);
        $username = $this->_db->loadResult();

        return ($username == '') ? JText::_('user deleted') : $username;
    }

    public function getSenderName()
    {
        if ($this->isSystemMessage()) {
            return JText::_('System');
        }

        $query = ' SELECT username'
            . ' FROM #__users'
            . ' WHERE id = ' . $this->sender_id;
        $this->_db->setQuery($query);
        $username = $this->_db->loadResult();

        return ($username == '') ? JText::_('user deleted') : $username;
    }

    public function isSystemMessage()
    {
        return ($this->sender_id == 0);
    }

    public function isSent()
    {
        $user = JFactory::getUser();

        return ($this->sender_id == $user->id);
    }

    public function isReceived()
    {
        return !$this->isSent();
    }

    public function softDelete()
    {
        if ($this->isReceived()) {
            $this->deleted_by_receiver = 1;
        }

        if ($this->isSent()) {
            $this->deleted_by_sender = 1;
        }

        $this->store();
    }

    public function deleteByReceiver()
    {
        if ($this->deleted_by_sender || 0 == $this->sender_id) {
            $this->delete();
        } else {
            $this->deleted_by_receiver = 1;
            $this->store();
        }

        return true;
    }

    public function delete($pk = null)
    {
        $user = JFactory::getUser();

        if ($user->id == $this->sender_id) {
            // Message was sent by the user
            if ($this->deleted_by_receiver || $user->id == $this->receiver_id) {
                parent::delete();
            } else {
                $this->deleted_by_sender = 1;
                $this->store();
            }
        } else {
            // Message was received by the user
            if ($this->deleted_by_sender || $user->id == $this->sender_id) {
                parent::delete();
            } else {
                $this->deleted_by_receiver = 1;
                $this->store();
            }
        }

        return true;
    }

    public function approve()
    {
        $this->approved = 1;

        if (!$this->store()) {
            return false;
        }

        JEventDispatcher::getInstance()->trigger('onLoveFactoryMessageApproved', array(
            'com_lovefactory.message_approved',
            $this
        ));

        return true;
    }

    public function reject()
    {
        $this->deleted_by_sender = 1;
        $this->deleted_by_receiver = 1;

        return $this->delete();
    }

    public function report()
    {
        $this->reported = 1;

        return $this->store();
    }

    public function getSenderUsername()
    {
        $table = JTable::getInstance('Profile', 'Table');
        $table->load($this->sender_id);

        return $table->display_name;
    }

    public function getReceiverUsername()
    {
        $table = JTable::getInstance('Profile', 'Table');
        $table->load($this->receiver_id);

        return $table->display_name;
    }

    protected function isApproved()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if (!$settings->approval_messages) {
            return true;
        }

        return $this->approved;
    }
}
