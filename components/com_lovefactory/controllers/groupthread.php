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

class FrontendControllerGroupThread extends FrontendController
{
    // Delete post
    public function deletePost()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $model = $this->getModel('GroupThread');

        if ($model->deletePost($id)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('groupthread_delete_post_success');
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('groupthread_delete_post_error');
            $response['error'] = $model->getError();
        }

        $this->renderJson($response);

        return false;
    }

    // Add thread
    public function addThread()
    {
        $data = JFactory::getApplication()->input->get('data', array(), 'array');
        $model = $this->getModel('GroupThread');
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if ($model->addThread($data)) {
            $msg = FactoryText::plural('groupthread_task_addthread_success', $settings->approval_group_threads);
            $link = FactoryRoute::view('groupthreads&id=' . $data['group_id']);
        } else {
            $msg = FactoryText::_('groupthread_task_addthread_error');
            $link = FactoryRoute::view('groupthreadedit&id=' . $data['group_id']);
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
        }

        $this->setRedirect($link, $msg);

        return true;
    }

    // Delete thread
    public function deleteThread()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $model = $this->getModel('GroupThread');

        if ($model->deleteThread($id)) {
            $msg = FactoryText::_('groupthread_delete_thread_success');
            $link = FactoryRoute::view('groupthreads&id=' . $model->getState('group_id'));
        } else {
            $msg = FactoryText::_('groupthread_delete_thread_error');
            $link = FactoryRoute::view('groupthread&id=' . $id);
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
        }

        $this->setRedirect($link, $msg);

        return false;
    }

    // Add post
    public function addPost()
    {
        $data = JFactory::getApplication()->input->get('data', array(), 'array');
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $model = $this->getModel('GroupThread');

        $data['thread_id'] = $id;

        if ($model->addPost($data)) {
            $msg = FactoryText::_('groupthread_task_addpost_success');
        } else {
            $msg = FactoryText::_('groupthread_task_addpost_error');
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
        }

        $this->setRedirect(FactoryRoute::view('groupthread&id=' . $id), $msg);

        return true;
    }

    // Report post
    public function reportPost()
    {
        $model = $this->getModel('GroupThread');
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $response = array();

        if ($model->reportPost($id)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('groupthread_task_reportpost_success');
            $response['text'] = FactoryText::_('groupthread_reported');
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('groupthread_task_reportpost_error');
            $response['error'] = $model->getError();
        }

        $this->renderJson($response);

        return true;
    }
}
