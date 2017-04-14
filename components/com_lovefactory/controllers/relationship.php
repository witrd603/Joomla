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

class FrontendControllerRelationship extends FrontendController
{
    public function __construct($config = array())
    {
        parent::__construct($config);

        JLoader::register('JHtmlLoveFactory', LoveFactoryApplication::getInstance()->getPath('component_site') . DS . 'lib' . DS . 'html' . DS . 'html.php');
    }

    public function request()
    {
        $model = $this->getModel('Relationship');
        $userId = JFactory::getApplication()->input->getInt('user_id', 0);
        $response = array();

        if ($model->request($userId)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('relationship_task_request_success');
            $response['html'] = JHtml::_('LoveFactory.RelationshipButton', $userId, 2);
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('relationship_task_request_error');
            $response['error'] = $model->getError();
            $response['redirect'] = $model->getState('redirect', false);
        }

        $this->renderJson($response);

        return true;
    }

    public function cancel()
    {
        $model = $this->getModel('Relationship');
        $userId = JFactory::getApplication()->input->getInt('id', 0);
        $response = array();

        if ($model->cancel($userId)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('relationship_task_cancel_success');
            $response['html'] = JHtml::_('LoveFactory.RelationshipButton', $userId, 0);
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('relationship_task_cancel_error');
            $response['error'] = $model->getError();
            $response['redirect'] = $model->getState('redirect', false);
        }

        $this->renderJson($response);

        return true;
    }

    public function remove()
    {
        $app = JFactory::getApplication();
        $model = $this->getModel('Relationship');
        $userId = $app->input->getInt('id', 0);
        $response = array();

        if ($model->remove($userId)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('relationship_task_remove_success');
            $response['html'] = JHtml::_('LoveFactory.RelationshipButton', $userId, 0);
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('relationship_task_remove_error');
            $response['error'] = $model->getError();
            $response['redirect'] = $model->getState('redirect', false);
        }

        if ($this->isAjaxRequest()) {
            $this->renderJson($response);
            return true;
        }

        if (!$response['status']) {
            $app->enqueueMessage($response['error'], 'error');
        }

        $this->setRedirect(FactoryRoute::view('myrelationship'), $response['message']);

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
}
