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

class FrontendModelBlacklist extends FactoryModel
{
    public function add($userId)
    {
        $user = JFactory::getUser();

        // Check if user is already blacklisted
        $is_blacklisted = $this->isBlacklisted($userId);

        if ($is_blacklisted) {
            $this->setError(FactoryText::_('blacklist_task_add_error_already_blocked'));
            return false;
        }

        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('same_gender_interaction');

        try {
            $restriction->isAllowed($user->id, $userId);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            $this->redirect_code = 9;
            return false;
        }

        $date = JFactory::getDate();
        $blacklist = $this->getTable('blacklist');

        $blacklist->sender_id = $user->id;
        $blacklist->receiver_id = $userId;
        $blacklist->date = $date->toSql();

        $blacklist->store();

        return true;
    }

    public function remove($users)
    {
        $user = JFactory::getUser();

        if (!is_array($users)) {
            $users = array($users);
        }

        foreach ($users as $userId) {
            $table = $this->getTable('Blacklist');
            $result = $table->load(array('sender_id' => $user->id, 'receiver_id' => $userId));

            if (!$result) {
                $this->setError(FactoryText::_('blacklist_task_remove_error_user_not_found'));
                return false;
            }

            if (!$table->delete()) {
                $this->setError($table->getError());
                return false;
            }
        }

        return true;
    }

    public function isBlacklisted($receiver_id, $sender_id = null)
    {
        $user = JFactory::getUser();
        $sender_id = ($sender_id == null) ? $user->id : $sender_id;

        $query = ' SELECT b.id'
            . ' FROM #__lovefactory_blacklist b'
            . ' WHERE b.sender_id = ' . $sender_id
            . ' AND b.receiver_id = ' . $receiver_id;
        $this->_db->setQuery($query);
        $result = $this->_db->loadResult();

        if ($result) {
            $this->setError(FactoryText::_('blacklist_error_you_have_been_blacklisted'));
            return true;
        }

        return false;
    }
}
