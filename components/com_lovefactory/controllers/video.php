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

class FrontendControllerVideo extends FrontendController
{
    public function setPrivacy()
    {
        $video_id = JFactory::getApplication()->input->getInt('video_id');
        $privacy = JFactory::getApplication()->input->getCmd('privacy');
        $model = $this->getModel('Video');
        $response = array();

        if ($model->setPrivacy($video_id, $privacy)) {
            $response['status'] = 1;
        } else {
            $response['status'] = 0;
        }

        $this->renderJson($response);

        return true;
    }

    public function add()
    {
        $response = array();
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $user = JFactory::getUser();
        $model = $this->getModel('Video');
        $data = JFactory::getApplication()->input->get('video', array(), 'array');
        $files = JFactory::getApplication()->input->files->get('video', array(), 'array');

        $data['user_id'] = $user->id;
        $data['thumbnail'] = $files['thumbnail'];

        if (isset($data['thumbnail_external'])) {
            $data['thumbnail'] = $data['thumbnail_external'];
        }

        if ($model->save($data, $files)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('video_task_add_success');
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('video_task_add_error');
            $response['error'] = $model->getError();
        }

        if ($this->isAjaxRequest()) {
            $this->renderJson($response);
            return true;
        }

        if (!$response['status']) {
            JFactory::getApplication()->enqueueMessage($response['error'], 'error');
        }

        $redirect = FactoryRoute::view('videos');

        if ($model->getState('membership_restriction_error', false) && $settings->invalid_membership_action) {
            $redirect = FactoryRoute::view('memberships');
        }

        $this->setRedirect($redirect, $response['message']);
        return true;
    }

    public function getYoutubeData()
    {
        if (!LoveFactoryApplication::getInstance()->getSettings('enable_youtube_integration')) {
            return false;
        }

        $model = $this->getModel('Video');
        $code = JFactory::getApplication()->input->get('code', array(), 'array');

        $response = $model->getYoutubeData($code[0]);

        $this->renderJson($response);

        return true;
    }
}
