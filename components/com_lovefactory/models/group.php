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

class FrontendModelGroup extends FactoryModel
{
    protected $item;

    public function getItem()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $table = $this->getTable('Group');

        if (!$table->load($id)) {
            throw new Exception(FactoryText::_('group_error_not_found_text'), 404);
        }

        if (!$table->isApproved() && !$table->isOwner()) {
            throw new Exception(FactoryText::_('group_error_not_found_text'), 404);
        }

        // Check if user is banned.
        if (!$table->isOwner()) {
            $banned = JTable::getInstance('GroupBan', 'Table');
            if ($banned->load(array('group_id' => $id, 'user_id' => JFactory::getUser()->id))) {
                JFactory::getApplication()->redirect(FactoryRoute::view('groups'), FactoryText::_('group_user_banned'));
                throw new Exception(FactoryText::_('group_error_not_found_text'), 404);
            }
        }

        $this->item = $table;

        return $this->item;
    }

    public function getMembers()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->select('COUNT(m.id)')
            ->from('#__lovefactory_group_members m')
            ->where('m.group_id = ' . $dbo->quote($id));

        $result = $dbo->setQuery($query)
            ->loadResult();

        return $result;
    }

    public function getThreads()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->select('COUNT(t.id)')
            ->from('#__lovefactory_group_threads t')
            ->where('t.group_id = ' . $dbo->quote($id));

        $result = $dbo->setQuery($query)
            ->loadResult();

        return $result;
    }

    public function getPosts()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->select('COUNT(p.id)')
            ->from('#__lovefactory_group_posts p')
            ->where('p.group_id = ' . $dbo->quote($id));

        $result = $dbo->setQuery($query)
            ->loadResult();

        return $result;
    }

    public function getOwner()
    {
        if (!$this->item) {
            return false;
        }

        $table = $this->getTable('Profile', 'Table');
        $table->load($this->item->user_id);

        return $table->display_name;
    }

    public function getIsMember($groupId = null)
    {
        if (!$this->item) {
            return false;
        }

        $table = $this->getTable('GroupMember');

        if (is_null($groupId)) {
            $groupId = $this->item->id;
        }

        if (!$table->load(array('group_id' => $groupId, 'user_id' => JFactory::getUser()->id))) {
            return false;
        }

        return $table->id;
    }

    public function getIsOwner()
    {
        if (!$this->item) {
            return false;
        }

        return $this->item->user_id == JFactory::getUser()->id;
    }

    public function leave($groupId)
    {
        $this->getItem($groupId);

        // Check if user is a group member
        if (false === $memberId = $this->getIsMember($groupId)) {
            $this->setError(FactoryText::_('group_task_leave_error_not_member'));
            return false;
        }

        $table = $this->getTable('GroupMember');

        $table->load($memberId);

        if (!$table->delete($memberId)) {
            $this->setError($table->getError());
            return false;
        }

        JEventDispatcher::getInstance()->trigger('onLoveFactoryMemberLeftGroup', array(
            'com_lovefactory.member_left_group',
            $table,
        ));

        return true;
    }

    public function join($groupId)
    {
        // Initialise variables.
        $user = JFactory::getUser();
        $dbo = JFactory::getDbo();
        $table = $this->getTable('Group');

        // Check if user is allowed to join groups.
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('groups_join');
        try {
            $restriction->isAllowed($user->id);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            $this->setState('membership_restriction_error', true);

            return false;
        }

        // Check if user is a group member.
        if (false !== $this->getIsMember($groupId)) {
            $this->setError(FactoryText::_('group_task_join_error_already_member'));
            return false;
        }

        // Load the group.
        if (!$table->load($groupId)) {
            $this->setError(FactoryText::_('group_task_join_error_group_not_found'));
            return false;
        }

        // Check if the user is the owner of the group.
        if ($table->user_id == $user->id) {
            $this->setError(FactoryText::_('group_task_join_error_group_owner'));
            return false;
        }

        // Check if the group has been approved.
        if (!$table->isApproved()) {
            $this->setError($table->getError());
            return false;
        }

        // Check if the user has been banned from the group.
        $query = $dbo->getQuery(true)
            ->select('b.id')
            ->from('#__lovefactory_group_bans b')
            ->where('b.user_id = ' . $dbo->quote($user->id))
            ->where('b.group_id = ' . $dbo->quote($groupId));
        $result = $dbo->setQuery($query)
            ->loadResult();

        if ($result) {
            $this->setError(FactoryText::_('group_task_join_error_you_have_been_banned'));
            return false;
        }

        // All is ok, add user to the group.
        $member = $this->getTable('GroupMember');

        $member->user_id = $user->id;
        $member->group_id = $groupId;
        $member->created_at = JFactory::getDate()->toSql();

        if (!$member->store()) {
            $this->setError($member->getError());
            return false;
        }

        JEventDispatcher::getInstance()->trigger('onLoveFactoryUserJoinedGroup', array(
            'com_lovefactory.user_joined_group',
            $member,
            $table
        ));

        return true;
    }

    public function save($data)
    {
        /* @var $profile TableProfile */
        $table = $this->getTable('Group');
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $user = JFactory::getUser();
        $isNew = false;

        if (!$data['id']) {
            // Check if group creation is allowed
            if (!$settings->groups_allow_users_create) {
                $this->setError(FactoryText::_('groupedit_group_create_not_allowed'));
                return false;
            }

            // Check if user is allowed to create a new group.
            $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('groups_create');
            try {
                $restriction->isAllowed($user->id);
            } catch (Exception $e) {
                $this->setError($e->getMessage());
                $this->setState('membership_restriction_error', true);

                return false;
            }

            $isNew = true;
        } else {
            $table->load($data['id']);

            if ($table->user_id != $user->id) {
                $this->setError(FactoryText::_('groupedit_edit_not_allowed'));
                return false;
            }
        }

        if (!$table->save($data)) {
            $this->setError($table->getError());
            return false;
        }

        $this->setState('id', $table->id);

        // Send admin approval notification.
        if ($isNew && $settings->approval_groups) {
            $mailer = FactoryMailer::getInstance();
            $mailer->sendAdminNotification(
                'item_pending_approval',
                array(
                    'item_type' => 'group',
                ));
        }

        if ($isNew) {
            // All is ok, add user to the group.
            $member = $this->getTable('GroupMember');

            $member->user_id = $user->id;
            $member->group_id = $table->id;
            $member->created_at = JFactory::getDate()->toSql();

            if (!$member->store()) {
                return false;
            }
        }

        JEventDispatcher::getInstance()->trigger('onLoveFactoryGroupAfterSave', array(
            'com_lovefactory.group',
            $table,
            $isNew
        ));

        return true;
    }

    public function delete($id)
    {
        $table = $this->getTable('Group');
        $table->load($id);

        // Check if it's my group.
        if (!$table->isMyGroup()) {
            $this->setError(FactoryText::_('group_task_delete_error_not_allowed'));
            return false;
        }

        if (!$table->delete()) {
            $this->setError($table->getError());
            return false;
        }

        JEventDispatcher::getInstance()->trigger('onLoveFactoryGroupRemoved', array(
            'com_lovefactory.group_removed',
            $table,
        ));

        return true;
    }
}
