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

class FrontendControllerFriend extends FrontendController
{
    public function __construct($config = array())
    {
        parent::__construct($config);

        JLoader::register('JHtmlLoveFactory', LoveFactoryApplication::getInstance()->getPath('component_site') . DS . 'lib' . DS . 'html' . DS . 'html.php');

        $this->registerTask('demote', 'promote');
    }

    public function request()
    {
        $model = $this->getModel('Friend');
        $userId = JFactory::getApplication()->input->post->getInt('user_id', 0);
        $response = array();

        if ($model->request($userId)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('friend_task_request_success');
            $response['html'] = JHtml::_('LoveFactory.FriendshipButton', $userId, 2);
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('friend_task_request_error');
            $response['error'] = $model->getError();
        }

        $this->checkMembershipRestrictionRedirection($model, $response);

        $this->renderJson($response);

        return true;
    }

    public function cancel()
    {
        $model = $this->getModel('Friend');
        $userId = JFactory::getApplication()->input->getInt('id', 0);
        $response = array();

        if ($model->cancel($userId)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('friend_task_cancel_success');
            $response['html'] = JHtml::_('LoveFactory.FriendshipButton', $userId, 0);
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('friend_task_cancel_error');
            $response['error'] = $model->getError();
        }

        $this->renderJson($response);

        return true;
    }

    public function remove()
    {
        $model = $this->getModel('Friend');
        $userId = JFactory::getApplication()->input->getInt('id', 0);
        $response = array();

        if ($model->remove($userId)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('friend task remove success');
            $response['html'] = JHtml::_('LoveFactory.FriendshipButton', $userId, 0);
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('friend task remove error');
            $response['error'] = $model->getError();
        }

        $this->renderJson($response);

        return true;
    }

    public function accept()
    {
        $model = $this->getModel('Friend');
        $userId = JFactory::getApplication()->input->getInt('id', 0);
        $response = array();

        $response['redirect'] = FactoryRoute::view('requests');

        if ($model->accept($userId)) {
            $response['message'] = FactoryText::_('friend_task_accept_success');
        } else {
            $response['message'] = FactoryText::_('friend_task_accept_error');
            $response['error'] = $model->getError();

            JFactory::getApplication()->enqueueMessage($response['error'], 'error');
        }

        $this->checkMembershipRestrictionRedirection($model, $response);

        $this->setRedirect($response['redirect'], $response['message']);
    }

    public function reject()
    {
        $model = $this->getModel('Friend');
        $userId = JFactory::getApplication()->input->getInt('id', 0);
        $link = FactoryRoute::view('requests');

        if ($model->reject($userId)) {
            $msg = FactoryText::_('friend_task_reject_success');
        } else {
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
            $msg = FactoryText::_('friend_task_reject_error');
        }

        $this->setRedirect($link, $msg);
    }

    public function promote()
    {
        $model = $this->getModel('Friend');
        $task = $this->getTask();
        $userId = JFactory::getApplication()->input->getInt('user_id', 0);
        $text = $task == 'promote' ? 'demote' : 'promote';
        $response = array();

        if ($model->promote($task, $userId)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('friend_task_' . $task . '_success');
            $response['text'] = FactoryText::_('topfriend_' . $text . '_top_friend');
            $response['html'] = JHtml::_('LoveFactory.TopFriendButton', $userId, $task == 'promote');
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('friend_task_' . $task . '_error');
            $response['error'] = $model->getError();
        }

        $this->checkMembershipRestrictionRedirection($model, $response);

        $this->renderJson($response);

        return true;
    }
}
