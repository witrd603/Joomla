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

class FrontendModelMessage extends FactoryModel
{
    public function send($data)
    {
        $session = JFactory::getSession();
        $session->set('com_lovefactory.message.compose', $data);

        // Initialise variables.
        $user = JFactory::getUser();
        $replyId = isset($data['reply_id']) ? $data['reply_id'] : 0;

        // Check if receiver is set.
        if ((!isset($data['receiver']) || '' == $data['receiver']) && (!isset($data['user_id']) || !$data['user_id'])) {
            $this->setError(FactoryText::_('message_task_send_error_user_not_set'));
            return false;
        }

        $profile = $this->getTable('Profile');

        // Load the receiver's profile.
        if (isset($data['receiver'])) {
            $profile->loadUsername($data['receiver']);
        } else {
            $profile->load($data['user_id']);
        }

        // Check if receiver exists.
        if ($profile->_is_new) {
            $this->setError(FactoryText::_('message_task_send_error_user_not_found'));
            return false;
        }

        // Check if sending to self
        if ($user->id == $profile->user_id) {
            $this->setError(FactoryText::_('message_task_send_error_send_to_self'));
            return false;
        }

        // Check if the user is blocked.
        $model = JModelLegacy::getInstance('Blacklist', 'FrontendModel');
        $isBlocked = $model->isBlacklisted($user->id, $profile->user_id);
        if ($isBlocked) {
            $this->setError($model->getError());
            return false;
        }

        // Check if this is a message reply.
        if ($replyId) {
            // Load original message.
            $originalMessage = $this->getTable('LoveFactoryMessage', 'Table');
            $originalMessage->load($replyId);

            // Check if original message was sent to the current user.
            if ($originalMessage->receiver_id != $user->id) {
                $replyId = 0;
            }
        }

        // Check if user is allowed to send messages to same gender
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('same_gender_interaction');

        try {
            $restriction->isAllowed($user->id, $profile->user_id);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            $this->setState('membership_restriction_error', true);

            return false;
        }

        $isFreeReply = false;

        // Check if this is a message reply and if message replies limit is not reached.
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('message_replies');

        if ($replyId) {
            try {
                $restriction->isAllowed($user->id);
                $isFreeReply = true;
            } catch (Exception $e) {
            }
        }

        if (!$replyId || !$isFreeReply) {
            // Check if messages limit is reached.
            $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('messages');

            try {
                $restriction->isAllowed($user->id);
            } catch (Exception $e) {
                $this->setError($e->getMessage());
                $this->setState('membership_restriction_error', true);

                return false;
            }
        }

        $data['title'] = isset($data['title']) ? strip_tags($data['title']) : '';
        $data['text'] = strip_tags($data['text']);

        $message = $this->getTable('LoveFactoryMessage');
        $message->send($profile->user_id, $user->id, $data['title'], $data['text']);

        // Update messages sent today.
        if ($isFreeReply) {
            $this->updateSentMessageReplies($user->id);
        } else {
            $this->updateSentMessages($user->id);
        }

        $session->set('com_lovefactory.message.compose', null);

        return true;
    }

    public function delete($messageId)
    {
        // Initialise variables.
        $user = JFactory::getUser();
        $table = $this->getTable('LoveFactoryMessage');

        // Load the message.
        if (!$table->load($messageId)) {
            $this->setError(FactoryText::_('message_task_delete_error_not_found'));
            return false;
        }

        // Check if the message belongs to the user.
        if (!$table->isSent() && !$table->isReceived()) {
            $this->setError(FactoryText::_('message_task_delete_error_not_found'));
            return false;
        }

        $this->setState('redirect', $table->isSent() ? 'outbox' : 'inbox');

        // Delete the message.
        if (!$table->delete()) {
            $this->setError($table->getError());
            return false;
        }

        return true;
    }

    function report()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $text = JFactory::getApplication()->input->getString('text', '');
        $user = JFactory::getUser();

        $message = $this->getTable('LoveFactoryMessage');
        $message->load($id);

        if ($user->id != $message->receiver_id) {
            $this->setError(JText::_('MESSAGE_TASK_REPORT_NOT_FOUND'));
            return false;
        }

        // Set message status as reported
        $message->reported = 1;
        $message->store();

        // Save the report
        $report = $this->getTable('report');
        $date = JFactory::getDate();

        $report->reporting_id = $user->id;
        $report->reported_id = $id;
        $report->user_id = $message->sender_id;
        $report->type_id = 1;
        $report->comment = $text;
        $report->text = $message->text;
        $report->date = $date->toSql();

        $report->store();

        return true;
    }

    function getMessage()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $user = JFactory::getUser();

        $query = ' SELECT m.*, s.username AS sender_username, r.username AS receiver_username,'
            . '   b.id AS blacklisted, IF (m.sender_id = ' . $user->id . ', 1, 0) AS my_message'
            . ' FROM #__lovefactory_messages m'
            . ' LEFT JOIN #__users s ON s.id = m.sender_id'
            . ' LEFT JOIN #__users r ON r.id = m.receiver_id'
            . ' LEFT JOIN #__lovefactory_blacklist b ON b.sender_id = ' . $user->id . ' AND b.receiver_id = m.sender_id'
            . ' WHERE m.id = ' . $id;
        $this->_db->setQuery($query);
        $message = $this->_db->loadObject();

        if (!$message ||
            ($user->id != $message->receiver_id &&
                $user->id != $message->sender_id)
        ) {
            return false;
        }

        $table = $this->getTable('LoveFactoryMessage', 'Table');
        $table->bind($message);

        $table->_sender_username = $message->sender_username;
        $table->_receiver_username = $message->receiver_username;
        $table->_blacklisted = $message->blacklisted;
        $table->_my_message = $message->my_message;

        if ('' == $table->title) {
            $table->title = JText::_('MESSAGE_UNTITLED');
        }

        if (!$table->_my_message && $table->unread) {
            $table->unread = 0;
            $table->store();
        }

        $table->date = TheFactoryHelper::date($table->date);

        return $table;
    }

    function getTo()
    {
        $to = JFactory::getApplication()->input->getInt('to', 0);
        $message_id = JFactory::getApplication()->input->getInt('message_id', 0);

        if (0 != $to) {
            $query = ' SELECT u.username'
                . ' FROM #__users u'
                . ' WHERE u.id = ' . $to;
            $this->_db->setQuery($query);
            $message = $this->_db->loadObject();

            return $message ? $message : false;
        } elseif (0 != $message_id) {
            $user = JFactory::getUser();

            $query = ' SELECT u.username, CONCAT("' . JText::_('RE:') . '", m.title) AS title'
                . ' FROM #__lovefactory_messages m'
                . ' LEFT JOIN #__users u ON u.id = m.sender_id'
                . ' WHERE m.id = ' . $message_id
                . ' AND m.receiver_id = ' . $user->id;
            $this->_db->setQuery($query);
            $message = $this->_db->loadObject();

            return $message ? $message : false;
        }

        return false;
    }

    public function getItem()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $user = JFactory::getUser();

        $table = $this->getTable('LovefactoryMessage');
        $table->load($id);

        if ($table->sender_id != $user->id && $table->receiver_id != $user->id) {
            return false;
        }

        // Mark message as read.
        if ($table->isReceived()) {
            $table->markAsRead();
        }

        return $table;
    }

    public function searchUser($term)
    {
        if ('' == $term) {
            return array();
        }

        $user = JFactory::getUser();
        $dbo = $this->getDbo();
        $array = array();

        $query = $dbo->getQuery(true)
            ->from('#__lovefactory_profiles p')
            ->where('p.banned = ' . $dbo->quote(0))
            ->where('p.user_id <> ' . $dbo->quote($user->id))
            ->where('(p.online = ' . $dbo->quote(0) . ' OR (p.online = ' . $dbo->quote(1) . ' AND f.id IS NOT NULL))')
            ->order('u.username ASC')
            ->group('u.id');

        $query->where('p.display_name LIKE ' . $dbo->quote('%' . $term . '%'));

        // Select username
        $query->select('u.username, p.display_name')
            ->leftJoin('#__users u ON u.id = p.user_id');

        // Select friendship.
        $query->leftJoin('#__lovefactory_friends f ON ((f.sender_id = p.user_id AND f.receiver_id = ' . $dbo->quote($user->id) . ') OR (f.sender_id = ' . $dbo->quote($user->id) . ' AND f.receiver_id = p.user_id)) AND f.pending = ' . $dbo->quote(0));

        $results = $dbo->setQuery($query)
            ->loadObjectList();

        foreach ($results as $result) {
            $array[] = array('name' => $result->display_name, 'id' => $result->username);
        }

        return $array;
    }

    protected function updateSentMessageReplies($userId)
    {
        $table = $this->getTable('statisticsPerDay');

        return $table->updateMessageReplies($userId);
    }

    protected function updateSentMessages($userId)
    {
        $table = $this->getTable('statisticsPerDay');

        return $table->updateMessages($userId);
    }
}
