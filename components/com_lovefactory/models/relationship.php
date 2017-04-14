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

class FrontendModelRelationship extends FactoryModel
{
    public function cancel($userId)
    {
        $user = JFactory::getUser();
        $table = $this->getTable('Friend');
        $return = $table->load(array('sender_id' => $user->id, 'receiver_id' => $userId, 'type' => 2, 'pending' => 1));

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

        // Check if sending request to self
        if ($userId == $user->id) {
            $this->setError(FactoryText::_('relationship_task_request_error_self_request'));
            return false;
        }

        // Check if user is blacklisted
        $model = JModelLegacy::getInstance('Blacklist', 'FrontendModel');
        if ($model->isBlacklisted($user->id, $userId)) {
            $this->setError($model->getError());
            return false;
        }

        // Check if user is allowed to interact with members of same gender.
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('same_gender_interaction');

        try {
            $restriction->isAllowed($user->id, $userId);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            $this->setState('membership_restriction_error', true);
            return false;
        }

        // Check if request already sent or friends already
        $model = JModelLegacy::getInstance('Friend', 'FrontendModel');
        if ($model->usersInRelationship($user->id, $userId)) {
            $this->setError($model->getError());
            return false;
        }

        $message = JFactory::getApplication()->input->getString('message', '');

        $friend = $this->getTable('Friend');
        $friend->request($user->id, $userId, $message, 2);

        JEventDispatcher::getInstance()->trigger('onLoveFactoryRelationshipRequestSent', array(
            'com_lovefactory.relationship_request.after', $friend,
        ));

        return true;
    }

    public function remove($userId)
    {
        $model = JModelLegacy::getInstance('Friend', 'FrontendModel');
        $friendship = $model->getFriendship($userId, 2);

        if (!$friendship || 1 == $friendship->pending) {
            $this->setError(FactoryText::_('relationship_task_remove_friend_not_found'));
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
}
