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

class FrontendControllerMyGallery extends FrontendController
{
    // Common
    function saveOrder()
    {
        $model = $this->getModel('mygallery');
        $response = array();

        if ($model->saveOrder()) {
            $response['status'] = 1;
            $response['message'] = JText::_('MY_GALLERY_TASK_SAVEORDER_SUCCESS');
        } else {
            $response['status'] = 0;
            $response['message'] = JText::_('MY_GALLERY_TASK_SAVEORDER_FAILURE') . '&nbsp;' . $model->getError();
        }

        $this->renderJson($response);
    }

    function delete()
    {
        $model = $this->getModel('mygallery');
        $response = array();

        if ($model->delete()) {
            $response['status'] = 1;
            $response['message'] = JText::_('MY_GALLERY_TASK_DELETE_SUCCESS');
        } else {
            $response['status'] = 0;
            $response['message'] = JText::_('MY_GALLERY_TASK_DELETE_FAILURE') . '&nbsp;' . $model->getError();
        }

        $this->renderJson($response);
    }

    function deleteBatch()
    {
        $model = $this->getModel('mygallery');
        $response = array();

        if ($model->deleteBatch()) {
            $response['status'] = 1;
            $response['message'] = JText::_('MY_GALLERY_TASK_DELETEBATCH_SUCCESS');
        } else {
            $response['status'] = 0;
            $response['message'] = JText::_('MY_GALLERY_TASK_DELETEBATCH_FAILURE') . '&nbsp;' . $model->getError();
        }

        $response['deleted'] = $model->_deleted;

        $this->renderJson($response);
    }

    // Photo
    function setAvatar()
    {
        $model = $this->getModel('mygallery');
        $response = array();

        if ($model->setAvatar()) {
            $response['status'] = 1;
            $response['message'] = JText::_('MY_GALLERY_TASK_SETAVATAR_SUCCESS');
            $response['main_id'] = $model->_main_id;
        } else {
            $response['status'] = 0;
            $response['message'] = JText::_('MY_GALLERY_TASK_SETAVATAR_FAILURE') . "\n" . $model->getError();
        }

        $this->renderJson($response);
    }

    function removeAvatar()
    {
        $model = $this->getModel('mygallery');
        $response = array();

        if ($model->removeAvatar()) {
            $response['status'] = 1;
        } else {
            $response['status'] = 0;
        }

        $this->renderJson($response);
    }

    function addGravatar()
    {
        $model = $this->getModel('mygallery');
        $response = array();

        if ($model->addGravatar()) {
            $response['status'] = 1;
            $response['message'] = JText::_('MY_GALLERY_TASK_ADDGRAVATAR_SUCCESS');
            $response['thumbnail'] = $model->_thumbnail_path;
            $response['photo_id'] = $model->_photo_id;
            $response['type'] = $model->_type;
            $response['code'] = 0;
        } else {
            $response['status'] = 0;
            $response['message'] = JText::_('MY_GALLERY_TASK_ADDGRAVATAR_FAILURE') . "\n\n" . $model->getError();
            $response['code'] = $model->_error_code;
        }

        $settings = new LovefactorySettings();
        if (2 == $response['code'] && !$settings->invalid_membership_action) {
            $response['code'] = 0;
        }

        $this->renderJson($response);
    }

    // Video
    function addVideo()
    {
        $settings = new LovefactorySettings();
        $model = $this->getModel('mygallery');
        $response = array();

        $view = $this->getView('MyGalleryVideo', 'html');
        $view->settings = $settings;

        if ($model->addVideo()) {
            $response['status'] = 1;
            $response['message'] = JText::_('MY_GALLERY_TASK_ADDVIDEO_SUCCESS');

            $view->video = $model->getItem();
            $response['list_item'] = $view->loadTemplate('video');
        } else {
            $response['status'] = 0;
            $response['message'] = JText::_('MY_GALLERY_TASK_ADDVIDEO_FAILURE');
            $response['error'] = $model->getError();
            $response['redirect'] = @$model->_redirect;
        }

        $this->renderJson($response);
    }
}
