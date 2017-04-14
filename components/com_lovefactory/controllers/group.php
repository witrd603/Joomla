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

class FrontendControllerGroup extends FrontendController
{
    public function join()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $model = $this->getModel('Group');
        $response = array();

        if ($model->join($id)) {
            $response['message'] = FactoryText::_('group_task_join_success');
        } else {
            $response['message'] = FactoryText::_('group_task_join_error');
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
        }

        $response['redirect'] = FactoryRoute::view('group&id=' . $id);

        $this->checkMembershipRestrictionRedirection($model, $response);

        $this->setRedirect($response['redirect'], $response['message']);

        return true;
    }

    public function leave()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $model = $this->getModel('Group');

        if ($model->leave($id)) {
            $msg = FactoryText::_('group_task_leave_success');
        } else {
            $msg = FactoryText::_('group_task_leave_error');
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
        }

        $this->setRedirect(FactoryRoute::view('group&id=' . $id), $msg);

        return true;
    }

    public function save()
    {
        $model = $this->getModel('Group');
        $data = JFactory::getApplication()->input->post->get('group', array(), 'array');
        $id = JFactory::getApplication()->input->get->getInt('id', 0);
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $response = array();

        $data['id'] = $id;

        if ($model->save($data)) {
            if (!$id && $settings->approval_groups) {
                $response['message'] = FactoryText::_('group_task_save_success_approval');
            } else {
                $response['message'] = FactoryText::_('group_task_save_success');
            }

            $id = $model->getState('id');
        } else {
            $response['message'] = FactoryText::_('group_task_save_error');
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
        }

        $response['redirect'] = FactoryRoute::view('groupedit&id=' . $id);

        $this->checkMembershipRestrictionRedirection($model, $response);

        $this->setRedirect($response['redirect'], $response['message']);

        return true;
    }

    public function removeBanned()
    {
        $batch = JFactory::getApplication()->input->get('batch', array(), 'array');
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $model = $this->getModel('GroupBanned');

        if ($model->removeUsersForGroup($batch, $id)) {
            $msg = FactoryText::_('group_task_removebanned_success');
        } else {
            $msg = FactoryText::_('group_task_removebanned_error');
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
        }

        $this->setRedirect(FactoryRoute::view('groupbanned&id=' . $id), $msg);
    }

    public function removeUsers()
    {
        $batch = JFactory::getApplication()->input->get('batch', array(), 'array');
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $model = $this->getModel('GroupMembers');

        if ($model->removeUsers($batch, $id)) {
            $msg = FactoryText::_('group_task_removeusers_success');
        } else {
            $msg = FactoryText::_('group_task_removeusers_error');
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
        }

        $this->setRedirect(FactoryRoute::view('groupmembers&id=' . $id), $msg);
    }

    public function banUsers()
    {
        $batch = JFactory::getApplication()->input->get('batch', array(), 'array');
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $model = $this->getModel('GroupBanned');

        if ($model->banUsers($batch, $id)) {
            $msg = FactoryText::_('group_task_banusers_success');
        } else {
            $msg = FactoryText::_('group_task_banusers_error');
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
        }

        $this->setRedirect(FactoryRoute::view('groupmembers&id=' . $id), $msg);
    }

    function post()
    {
        $model = $this->getModel('Group', 'FrontendModel');
        $response = array();

        if ($model->post()) {
            $settings = new LovefactorySettings();
            $Itemid = JFactory::getApplication()->input->getInt('Itemid', 0);

            $view = $this->getView('Group', 'html', 'FrontendView');
            $view->reply = $model->_reply;
            $view->post = $model->_reply;
            $view->group = $model->_group;
            $view->Itemid = $Itemid;

            $response['status'] = 1;
            $response['text'] = $settings->approval_groups_posts ? '<div>' . JText::_('COM_LOVEFACTORY_GROUP_POST_SUCCESS_APPROVAL') . '</div>' : $view->loadTemplate($model->_reply->parent_id ? 'reply' : 'post');
        } else {
            $response['status'] = 0;
            $response['message'] = JText::_('GROUP_TASK_POST_FAILURE');
            $response['error'] = $model->getError();
        }

        $this->renderJson($response);
    }

    function deletePost()
    {
        $model = $this->getModel('Group', 'FrontendModel');
        $response = array();

        if ($model->deletePost()) {
            $response['status'] = 1;
        } else {
            $response['status'] = 0;
            $response['error'] = $model->getError();
        }

        $this->renderJson($response);
    }

    public function delete()
    {
        $model = $this->getModel('Group');
        $id = JFactory::getApplication()->input->getInt('id', 0);

        if ($model->delete($id)) {
            $msg = FactoryText::_('group_task_delete_success');
            $link = FactoryRoute::view('groups');
        } else {
            $msg = FactoryText::_('group_task_delete_error');
            $link = FactoryRoute::view('groupedit&id=' . $id);
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
        }

        $this->setRedirect($link, $msg);

        return true;
    }
}
