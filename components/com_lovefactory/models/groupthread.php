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

class FrontendModelGroupThread extends FactoryModel
{
    protected $thread;

    public function getThread()
    {
        if (!isset($this->thread)) {
            $this->thread = $this->getTable('GroupThread');

            if (!$this->thread->load($this->getThreadId())) {
                throw new Exception(FactoryText::_('groupthread_thread_not_found'), 404);
            }

            if ($this->thread && !$this->thread->isApproved() && !$this->thread->isOwner()) {
                throw new Exception(FactoryText::_('groupthread_thread_not_available'), 403);
            }
        }

        return $this->thread;
    }

    public function getThreadId()
    {
        return JFactory::getApplication()->input->getInt('id', 0);
    }

    public function getPosts()
    {
        return $this->getPostsModel()->getItems($this->getThreadId());
    }

    public function getPagination()
    {
        return $this->getPostsModel()->getPagination();
    }

    public function addPost($data)
    {
        // Initialise variables.
        $user = JFactory::getUser();

        // Get thread.
        $thread = $this->getTable('GroupThread');
        if (!$thread->load($data['thread_id'])) {
            $this->setError(FactoryText::_('group_task_addpost_error_thread_not_found'));
            return false;
        }

        // Check if the thread has been approved.
        if (!$thread->isApproved()) {
            $this->setError($thread->getError());
            return false;
        }

        // Get group.
        $group = $this->getTable('Group');
        if (!$group->load($thread->group_id)) {
            $this->setError(FactoryText::_('group_task_addpost_error_thread_not_found'));
            return false;
        }

        // Check if allowed to post in group.
        $member = $this->getTable('GroupMember');
        if (!$group->isMyGroup() && $group->private && !$member->load(array('user_id' => $user->id, 'group_id' => $group->id))) {
            $this->setError(FactoryText::_('group_task_addpost_error_not_allowed_to_post'));
            return false;
        }

        // Check if the group has been approved.
        if (!$group->isApproved()) {
            $this->setError($group->getError());
            return false;
        }

        // Check if the user has been banned.
        $table = $this->getTable('GroupBan', 'Table');
        if ($table->load(array('group_id' => $group->id, 'user_id' => $user->id))) {
            $this->setError(FactoryText::_('group_task_addpost_error_you_are_banned'));
            return false;
        }

        // Prepare data
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $explode = explode(',', $settings->groups_post_allowed_html);
        foreach ($explode as &$tag) {
            $tag = '<' . trim($tag) . '>';
        }
        $data['text'] = strip_tags($data['text'], implode('', $explode));

        // Store the post.
        $table = $this->getTable('GroupPost');
        $table->group_id = $group->id;

        if (!$table->save($data)) {
            $this->setError($table->getError());
            return false;
        }

        // Send admin approval notification.
        if ($settings->approval_groups_posts) {
            // Send notifications.
            $mailer = FactoryMailer::getInstance();
            $mailer->sendAdminNotification(
                'item_pending_approval',
                array(
                    'item_type' => 'grouppost',
                ));
        }

        return true;
    }

    public function reportPost($id)
    {
        // Initialise variables.
        $table = $this->getTable('GroupPost');

        // Load the comment.
        $result = $table->load($id);

        // Check if comment was found.
        if (!$result) {
            $this->setError(FactoryText::_('groupthread_task_reportpost_error_post_not_found'));
            return false;
        }

        // Report comment.
        if (!$table->report()) {
            $this->setError($table->getError());
            return false;
        }

        return true;
    }

    public function deletePost($id)
    {
        // Initialise variables.
        $user = JFactory::getUser();

        // Check if post exists.
        $post = $this->getTable('GroupPost');
        if (!$post->load($id)) {
            $this->setError(FactoryText::_('groupthread_task_deletepost_error_not_found'));
            return false;
        }

        // Load post's group.
        $group = $this->getTable('Group');
        $group->load($post->group_id);

        // Check if post is mine.
        if ($post->user_id != $user->id && !$group->isMyGroup()) {
            $this->setError(FactoryText::_('groupthread_task_deletepost_not_allowed'));
            return false;
        }

        // Delete the post.
        if (!$post->delete()) {
            $this->setError($post->getError());
            return false;
        }

        return true;
    }

    public function deleteThread($id)
    {
        // Initialise variables.
        $user = JFactory::getUser();
        $thread = $this->getTable('GroupThread');

        // Load the thread.
        if (!$thread->load($id)) {
            $this->setError(FactoryText::_('groupthread_task_deletethread_error_not_found'));
            return false;
        }

        // Check if user is group owner or thread owner.
        if ($thread->user_id != $user->id && !$thread->isMyGroup()) {
            $this->setError(FactoryText::_('groupthread_task_deletethread_not_allowed'));
            return false;
        }

        // Delete the thread.
        if (!$thread->delete()) {
            $this->setError($thread->getError());
            return false;
        }

        $this->setState('group_id', $thread->group_id);

        return true;
    }

    public function addThread($data)
    {
        // Initialise variables.
        $group = $this->getTable('Group');
        $user = JFactory::getUser();
        $table = $this->getTable('GroupThread');

        // Check if group exists.
        if (!$group->load($data['group_id'])) {
            $this->setError(FactoryText::_('groupthread_task_addthread_error_group_not_found'));
            return false;
        }

        // Check if user is group member or group owner.
        if (!$group->userIsMember() && !$group->isMyGroup()) {
            $this->setError(FactoryText::_('groupthread_task_addthread_error_not_allowed'));
            return false;
        }

        // Check if the group has been approved.
        if (!$group->isApproved()) {
            $this->setError($group->getError());
            return false;
        }

        // Prepare data
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $explode = explode(',', $settings->groups_post_allowed_html);
        foreach ($explode as &$tag) {
            $tag = '<' . trim($tag) . '>';
        }
        $data['title'] = strip_tags($data['title']);
        $data['text'] = strip_tags($data['text'], implode('', $explode));

        // Save the thread.
        if (!$table->save($data)) {
            $this->setError($table->getError());
            return false;
        }

        // Send admin approval notification.
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        if ($settings->approval_group_threads) {
            // Send notifications.
            $mailer = FactoryMailer::getInstance();
            $mailer->sendAdminNotification(
                'item_pending_approval',
                array(
                    'item_type' => 'groupthread',
                ));
        }

        return true;
    }

    public function getApproval()
    {
        return $this->getPostsModel()->getApproval();
    }

    public function getRouteDeleteComment()
    {
        return FactoryRoute::task('groupthread.deletepost', false, -1);
    }

    protected function getPostsModel()
    {
        static $model = null;

        if (is_null($model)) {
            $model = JModelLegacy::getInstance('GroupPosts', 'FrontendModel');
        }

        return $model;
    }
}
