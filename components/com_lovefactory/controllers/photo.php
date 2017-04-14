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

class FrontendControllerPhoto extends FrontendController
{
    public function upload()
    {
        $batch = JFactory::getApplication()->input->files->get('batch', array(), 'array');
        $privacy = JFactory::getApplication()->input->post->get('privacy', array(), 'array');
        $model = $this->getModel('Photo');
        $response = array();

        if ($model->upload($batch, $privacy)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('photo_task_upload_success');
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('photo_task_upload_error');
            $response['error'] = $model->getError();
        }

        $response['photos'] = $model->getState('photos', array());

        if (!$response['status']) {
            JFactory::getApplication()->enqueueMessage($response['error'], 'error');
            $this->checkMembershipRestrictionRedirection($model, $response);
        }

        $this->renderJson($response);

        return true;
    }

    public function setMain()
    {
        $batch = JFactory::getApplication()->input->get('batch', array(), 'array');
        $model = $this->getModel('Photo');
        $response = array();

        if ($model->setMain($batch)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('photo_task_setmain_success');
            $response['photo_id'] = $model->getState('photoId');
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('photo_task_setmain_error');
            $response['error'] = $model->getError();
        }

        $this->renderJson($response);

        return true;
    }

    public function addGravatar()
    {
        $model = $this->getModel('Photo');

        if ($model->addGravatar()) {
            $msg = FactoryText::_('photo_task_addgravatar_success');
        } else {
            $msg = FactoryText::_('photo_task_addgravatar_error');
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
        }

        $this->setRedirect(FactoryRoute::view('photos'), $msg);
    }
}
