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

jimport('joomla.application.component.model');

class FrontendModelMailbox extends FactoryModel
{
    function emptyOutbox()
    {
        $user = JFactory::getUser();

        $query = ' UPDATE #__lovefactory_messages'
            . ' SET deleted_by_sender = 1'
            . ' WHERE sender_id = ' . $user->id;
        $this->_db->setQuery($query);
        $this->_db->execute($query);

        $this->deleteMessages();

        return true;
    }

    function emptyInbox()
    {
        $user = JFactory::getUser();

        $query = ' UPDATE #__lovefactory_messages'
            . ' SET deleted_by_receiver = 1'
            . ' WHERE receiver_id = ' . $user->id;
        $this->_db->setQuery($query);
        $this->_db->execute($query);

        $this->deleteMessages();

        return true;
    }

    function deleteMessages()
    {
        $user = JFactory::getUser();

        $query = ' DELETE'
            . ' FROM #__lovefactory_messages'
            . ' WHERE deleted_by_receiver = 1'
            . ' AND (deleted_by_sender = 1 OR sender_id = 0)'
            . ' AND (sender_id = ' . $user->id . ' OR receiver_id = ' . $user->id . ')';
        $this->_db->setQuery($query);

        $this->_db->execute();
    }

    function delete()
    {
        $user = JFactory::getUser();
        $id = JFactory::getApplication()->input->getInt('id', 0);

        $message = $this->getTable('LoveFactoryMessage');
        $message->load($id);

        if (($message->sender_id != $user->id) &&
            ($message->receiver_id != $user->id)
        ) {
            return false;
        }

        $message->softDelete();

        return true;
    }

    function getCanSendMessage()
    {
        require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'settings.php');
        $settings = new LovefactorySettings();

        $statistics_per_day = $this->getTable('statisticsPerDay');
        $profile = $this->getTable('profile');

        $user = JFactory::getUser();
        $query = ' SELECT p.*, m.max_messages_per_day'
            . ' FROM #__lovefactory_profiles p'
            . ' LEFT JOIN #__lovefactory_memberships m ON m.id = p.membership'
            . ' WHERE p.user_id = ' . $user->id;
        $this->_db->setQuery($query);
        $profile = $this->_db->loadObject();

        $max_messages = $profile->max_messages_per_day;

        if ($max_messages == -1) {
            return true;
        }

        $already_sent = intval($statistics_per_day->getMessages($user->id));

        return ($already_sent < $max_messages);
    }
}
