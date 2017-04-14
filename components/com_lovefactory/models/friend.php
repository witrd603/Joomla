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

class FrontendModelFriend extends FactoryModel
{
    public function accept($userId)
    {
        // Initialise variables.
        $user = JFactory::getUser();

        // Check friends limit.
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('friends');

        try {
            $restriction->isAllowed($user->id);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            $this->setState('membership_restriction_error', true);

            return false;
        }

        // Load friendship request.
        $table = $this->getTable('Friend');
        $result = $table->load(array('sender_id' => $userId, 'receiver_id' => $user->id, 'pending' => 1));

        // Check if friendship request was found.
        if (!$result) {
            $this->setError(FactoryText::_('friend_task_accept_error_request_not_found'));
            return false;
        }

        // Check if it's relationship request and if users already have a relationship.
        if (2 == $table->type && $this->usersInRelationship($user->id, $userId)) {
            return false;
        }

        $table->accept();

        return true;
    }

    public function reject($userId)
    {
        // Initialise variables.
        $user = JFactory::getUser();

        // Load friendship request.
        $table = $this->getTable('Friend');
        $result = $table->load(array('sender_id' => $userId, 'receiver_id' => $user->id, 'pending' => 1));

        // Check if friendship request was found.
        if (!$result) {
            $this->setError(FactoryText::_('friend_task_accept_error_request_not_found'));
            return false;
        }

        $table->remove();

        return true;
    }

    public function cancel($userId)
    {
        $user = JFactory::getUser();
        $table = $this->getTable('Friend');
        $return = $table->load(array('sender_id' => $user->id, 'receiver_id' => $userId, 'type' => 1, 'pending' => 1));

        // Check if request exists.
        if (!$return) {
            $this->setError(FactoryText::_('friend_task_cancel_error_request_not_found'));
            return false;
        }

        if (!$table->delete()) {
            $this->setError($table->getError());
            return false;
        }

        return true;
    }

    public function request($userId)
    {
        // Initialise variables.
        $user = JFactory::getUser();

        // Check friends limit
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('friends');

        try {
            $restriction->isAllowed($user->id);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            $this->setState('membership_restriction_error', true);

            return false;
        }

        // Check if sending request to self
        if ($userId == $user->id) {
            $this->setError(FactoryText::_('friend_taks_request_error_self_request'));
            return false;
        }

        // Check if requests limit is reached.
        if ($this->requestsLimitReached($user->id, $userId)) {
            $this->setError(FactoryText::_('friend_taks_request_error_requests_limit_reached'));
            return false;
        }

        // Check if user is blacklisted
        $model = JModelLegacy::getInstance('Blacklist', 'FrontendModel');
        if ($model->isBlacklisted($user->id, $userId)) {
            $this->setError($model->getError());
            return false;
        }

        // Check if user is allowed to interact with members of same gender
        $my_profile = $this->getTable('Profile', 'Table');
        $profile = $this->getTable('Profile', 'Table');

        $my_profile->load($user->id);
        $profile->load($userId);

        // Check if user is allowed to interact with same gender.
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('same_gender_interaction');

        try {
            $restriction->isAllowed($user->id, $profile->user_id);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            $this->setState('membership_restriction_error', true);

            return false;
        }

        // Check if request already sent or friends already
        $query = ' SELECT id'
            . ' FROM #__lovefactory_friends'
            . ' WHERE ((sender_id = ' . $userId . ' AND receiver_id = ' . $user->id . ')'
            . ' OR (sender_id = ' . $user->id . ' AND receiver_id = ' . $userId . '))'
            . ' AND type = 1';
        $this->_db->setQuery($query);
        $result = $this->_db->loadResult();

        if ($result) {
            $this->setError(FactoryText::_('friend_task_request_error_alredy_friends_or_pending'));
            return false;
        }

        if (LoveFactoryApplication::getInstance()->getSettings('friendship_request_message')) {
            $message = JFactory::getApplication()->input->getString('message', '');
        } else {
            $message = '';
        }

        $friend = $this->getTable('Friend');
        $friend->request($user->id, $userId, $message);

        JEventDispatcher::getInstance()->trigger('onLoveFactoryFriendshipRequestSent', array(
            'com_lovefactory.friendship_request.after', $friend,
        ));

        return true;
    }

    public function remove($userId)
    {
        $friendship = $this->getFriendship($userId, 1);

        if (!$friendship || 1 == $friendship->pending) {
            $this->setError(FactoryText::_('friend task remove friend not found'));
            return false;
        }

        $table = $this->getTable('Friend', 'Table');
        $table->bind($friendship);

        if (!$table->remove()) {
            $this->setError($table->getError());
            return false;
        }

        return true;
    }

    public function promote($mode, $userId)
    {
        if ('promote' == $mode) {
            return $this->promoteFriend($userId);
        }

        return $this->demoteFriend($userId);
    }

    public function getFriendshipStatus($firstUser, $secondUser, $type = 1)
    {
        if (!$firstUser || !$secondUser) {
            return 0;
        }

        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('f.*')
            ->from('#__lovefactory_friends f')
            ->where('((f.sender_id = ' . $dbo->quote($firstUser) . ' AND f.receiver_id = ' . $dbo->quote($secondUser) . ') OR (f.sender_id = ' . $dbo->quote($secondUser) . ' AND f.receiver_id = ' . $dbo->quote($firstUser) . '))')
            ->where('f.type = ' . $dbo->quote($type));
        $result = $dbo->setQuery($query)
            ->loadObject();

        if (!$result) {
            return 0;
        }

        if ($result->pending) {
            return $firstUser == $result->sender_id ? 2 : 3;
        }

        return 1;
    }

    protected function promoteFriend($userId)
    {
        // Initialise variables.
        $friendship = $this->getFriendship($userId);
        $user = JFactory::getUser();

        // Check if users are friends.
        if (!$friendship || $friendship->pending == 1) {
            $this->setError(FactoryText::_('friend_task_promote_friend_not_found'));
            return false;
        }

        // Check if user is already a top friend.
        if (($friendship->sender_id == $user->id && $friendship->sender_status) ||
            ($friendship->receiver_id == $user->id && $friendship->receiver_status)
        ) {
            $this->setError(FactoryText::_('friend task promote already top friend'));
            return false;
        }

        // Check if top friends limit is reached.
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('friends_top');

        try {
            $restriction->isAllowed($user->id);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            $this->setState('membership_restriction_error', true);

            return false;
        }

        // Promote friend.
        $table = $this->getTable('Friend', 'Table');
        $table->id = $friendship->id;

        if ($friendship->sender_id == $user->id) {
            $table->sender_status = 1;
        } else {
            $table->receiver_status = 1;
        }

        if (!$table->store()) {
            $this->setError($table->getError());
            return false;
        }

        return true;
    }

    protected function demoteFriend($userId)
    {
        // Initialise variables.
        $friendship = $this->getFriendship($userId);
        $user = JFactory::getUser();

        // Check if users are friends.
        if (!$friendship || $friendship->pending == 1) {
            $this->setError(FactoryText::_('friend_task_promote_friend_not_found'));
            return false;
        }

        // Check if user is top friend.
        if (($friendship->sender_id == $user->id && !$friendship->sender_status) ||
            ($friendship->receiver_id == $user->id && !$friendship->receiver_status)
        ) {
            $this->setError(FactoryText::_('friend task demote not top friend'));
            return false;
        }

        // Demote friend.
        $table = $this->getTable('Friend', 'Table');
        $table->id = $friendship->id;

        if ($friendship->sender_id == $user->id) {
            $table->sender_status = 0;
        } else {
            $table->receiver_status = 0;
        }

        if (!$table->store()) {
            $this->setError($table->getError());
            return false;
        }

        return true;
    }

    public function getFriendship($userId, $type = 1)
    {
        $user = JFactory::getUser();
        $dbo = $this->getDbo();

        $query = $dbo->getQuery(true)
            ->select('f.*')
            ->from('#__lovefactory_friends f')
            ->where('((f.sender_id = ' . $dbo->quote($userId) . ' AND f.receiver_id = ' . $dbo->quote($user->id) . ') OR (f.sender_id = ' . $dbo->quote($user->id) . ' AND f.receiver_id = ' . $dbo->quote($userId) . '))')
            ->where('f.type = ' . $dbo->quote($type));
        $result = $dbo->setQuery($query)
            ->loadObject();

        return $result;
    }

    public function usersInRelationship($receiverId, $senderId)
    {
        $dbo = $this->getDbo();
        $users = array($dbo->quote($receiverId), $dbo->quote($senderId));

        $query = $dbo->getQuery(true)
            ->select('f.id, f.sender_id, f.receiver_id')
            ->from('#__lovefactory_friends f')
            ->where('(f.sender_id IN (' . implode(',', $users) . ') OR f.receiver_id IN (' . implode(',', $users) . '))')
            ->where('f.type = ' . $dbo->quote(2))
            ->where('f.pending = ' . $dbo->quote(0));
        $result = $dbo->setQuery($query)
            ->loadObject();

        if ($result) {
            if ($receiverId == $result->sender_id || $receiverId == $result->receiver_id) {
                $this->setError(FactoryText::_('friend_task_accept_error_you_already_are_in_a_relationship'));
            } else {
                $this->setError(FactoryText::_('friend_task_accept_error_requesting_user_already_is_in_a_relationship'));
            }

            return true;
        }

        return false;
    }

    protected function requestsLimitReached($senderId, $receiverId)
    {
        // Initialise variables.
        $limit = LoveFactoryApplication::getInstance()->getSettings('friendship_requests_limit');

        // Check if the restriction is enabled.
        if (!$limit) {
            return false;
        }

        // Get the number of requests sent.
        $requests = $this->getRequestsSent($senderId, $receiverId);

        return $requests >= $limit;
    }

    protected function getRequestsSent($senderId, $receiverId)
    {
        $dbo = $this->getDbo();
        $limit = JFactory::getDate('-1 day')->toSql();

        $query = $dbo->getQuery(true)
            ->select('COUNT(1)')
            ->from('#__lovefactory_friends_requests')
            ->where('sender_id = ' . $dbo->quote($senderId))
            ->where('receiver_id = ' . $dbo->quote($receiverId))
            ->where('created_at > ' . $dbo->quote($limit));

        return $dbo->setQuery($query)
            ->loadResult();
    }
}
