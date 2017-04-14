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

class FrontendModelGroupBanned extends FactoryModelList
{
    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->sort = array(
            '' => array('text' => FactoryText::_('groupmembers_filter_sort_member'), 'column' => 'p.display_name'),
            'since' => array('text' => FactoryText::_('groupmembers_filter_sort_since'), 'column' => 'b.created_at'),
        );
    }

    public function getGroup($id = null)
    {
        static $group = null;

        if (is_null($group)) {
            if (is_null($id)) {
                $id = JFactory::getApplication()->input->getInt('id', 0);
            }
            $group = $this->getTable('Group');

            if (!$group->load($id)) {
                throw new Exception(FactoryText::_('groupsbanned_group_not_found'), 404);
            }
        }

        return $group;
    }

    public function removeUsersForGroup($batch, $id)
    {
        $user = JFactory::getUser();
        JArrayHelper::toInteger($batch);

        // Check if batch is empty.
        if (!$batch) {
            $this->setError(FactoryText::_('batch_no_item_selected'));
            return false;
        }

        // Check if user is group owner.
        $table = $this->getTable('Group');
        $table->load($id);

        if ($table->user_id != $user->id) {
            $this->setError(FactoryText::_('groupbanned_task_remove_error_not_allowed'));
            return false;
        }

        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->delete()
            ->from('#__lovefactory_group_bans')
            ->where('user_id IN (' . implode(',', $batch) . ')')
            ->where('group_id = ' . $dbo->quote($id));

        return $dbo->setQuery($query)
            ->execute();
    }

    public function ban($userId, $groupId)
    {
        // Initialise variables.
        $data = array(
            'user_id' => $userId,
            'group_id' => $groupId,
        );

        // Check if group exists.
        $group = $this->getGroup($groupId);

        if (!$group) {
            $this->setError(FactoryText::_('group_ban_user_error_group_not_found'));
            return false;
        }

        // Check if user exists.
        $user = JFactory::getUser($userId);

        if (!$user->id) {
            $this->setError(FactoryText::_('group_ban_user_error_user_not_found'));
            return false;
        }

        // Check if user is group owner.
        if (!$group->isOwner()) {
            $this->setError(FactoryText::_('group_ban_user_error_not_allowed'));
            return false;
        }

        // Check if user banned is group owner.
        if ($group->user_id == $userId) {
            $this->setError(FactoryText::_('group_ban_user_error_cannot_ban_group_owner'));
            return false;
        }

        // Check if user is already banned.
        $table = $this->getTable('GroupBan', 'Table');
        if ($table->load($data)) {
            $this->setError(FactoryText::_('group_ban_user_error_user_already_banned'));
            return false;
        }

        // Ban user.
        if (!$table->save($data)) {
            $this->setError($table->getError());
            return false;
        }

        return true;
    }

    public function banUsers($batch, $id)
    {
        $user = JFactory::getUser();
        JArrayHelper::toInteger($batch);

        // Check if batch is empty.
        if (!$batch) {
            $this->setError(FactoryText::_('batch_no_item_selected'));
            return false;
        }

        // Check if user is group owner.
        $table = $this->getTable('Group');
        $table->load($id);

        if ($table->user_id != $user->id) {
            $this->setError(FactoryText::_('groupmembers_task_banusers_error_not_allowed'));
            return false;
        }

        foreach ($batch as $userId) {
            $this->ban($userId, $id);
        }

        return true;
    }

    protected function getListQuery()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $query = parent::getListQuery();

        $query->select('b.user_id, b.created_at')
            ->from('#__lovefactory_group_bans b')
            ->where('b.group_id = ' . $query->quote($id));

        $query->select('p.display_name')
            ->leftJoin('#__lovefactory_profiles AS p ON p.user_id = b.user_id');

        return $query;
    }

    protected function addFilterSearchCondition($query)
    {
        $value = $this->getFilterValue('search');

        if ('' != $value) {
            $query->where('p.display_name LIKE ' . $query->quote('%' . $value . '%'));
        }
    }
}
