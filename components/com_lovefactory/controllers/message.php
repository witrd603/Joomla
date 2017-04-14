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

class FrontendControllerMessage extends FrontendController
{
    public function send()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $model = $this->getModel('Message');
        $data = JFactory::getApplication()->input->get('message', array(), 'array');
        $response = array();

        if ($model->send($data)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::plural('message_task_send_success', $settings->approval_messages);
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('message_task_send_error');
            $response['error'] = $model->getError();
        }

        $this->checkMembershipRestrictionRedirection($model, $response);

        // Check if it's Ajax request.
        if ($this->isAjaxRequest()) {
            $this->renderJson($response);

            return true;
        }

        if ($response['status']) {
            if (!isset($response['redirect'])) {
                $response['redirect'] = FactoryRoute::view('outbox');
            }
        } else {
            if (!isset($response['redirect'])) {
                $response['redirect'] = FactoryRoute::view('compose');
            }

            JFactory::getApplication()->enqueueMessage($response['error'], 'error');
        }

        $this->setRedirect($response['redirect'], $response['message']);

        return true;
    }

    public function delete()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $model = $this->getModel('Message');

        if ($model->delete($id)) {
            $msg = FactoryText::_('message_task_delete_success');
            $url = FactoryRoute::view($model->getState('redirect'));
        } else {
            $msg = FactoryText::_('message_task_delete_error');
            $url = FactoryRoute::view('message&id=' . $id);
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
        }

        $this->setRedirect($url, $msg);
    }

    public function searchUser()
    {
        $model = $this->getModel('Message');
        $term = JFactory::getApplication()->input->getString('q', '');

        $results = $model->searchUser($term);

        $this->renderJson($results);

        return true;
    }
}
