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

class FrontendControllerGallery extends FrontendController
{
    function report()
    {
        $model = $this->getModel('gallery');
        $response = array();

        if ($model->report()) {
            $response['status'] = 1;
            $response['message'] = JText::_('GALLERY_TASK_REPORT_SUCCESS');
            $response['error'] = '';
        } else {
            $response['status'] = 0;
            $response['message'] = JText::_('GALLERY_TASK_REPORT_FAILURE');
            $response['error'] = $model->getError();

        }

        $this->renderJson($response);
    }

    function upload()
    {
        $model = $this->getModel('mygallery');
        $response = array();
        $settings = new LovefactorySettings();

        if ($model->upload()) {
            $response['status'] = 1;
            $response['message'] = JText::_('GALLERY_TASK_UPLOAD_SUCCESS');
            $response['thumbnail'] = $model->_thumbnail_path;
            $response['photo_id'] = $model->_photo_id;
            $response['type'] = $model->_type;
            $response['code'] = 0;
            $response['approved'] = $settings->approval_photos ? 0 : 1;
        } else {
            $response['status'] = 0;
            $response['message'] = JText::_('GALLERY_TASK_UPLOAD_FAILURE') . "\n\n" . $model->getError();
            $response['code'] = $model->_error_code;
        }

        $settings = new LovefactorySettings();
        if (2 == $response['code'] && !$settings->invalid_membership_action) {
            $response['code'] = 0;
        }

        $this->renderJson($response);
    }

    function uploadVideoThumbnail()
    {
        $model = $this->getModel('mygallery');
        $response = array();

        if ($model->uploadVideoThumbnail()) {
            $response['status'] = 1;
            $response['message'] = '';
            $response['filename'] = $model->_filename;
            $response['source'] = $model->_source;
        } else {
            $response['status'] = 0;
            $response['message'] = JText::_('MY_GALLERY_VIDEO_THUMBNAIL_UPLOAD_FAILURE') . '&nbsp;' . $model->getError();
        }

        $this->renderJson($response);
    }

    public function classicUpload()
    {
        $model = $this->getModel('mygallery');
        $return = $model->classicUpload();
        $uploaded = $model->getState('uploaded');

        if (!$return || !$uploaded) {
            foreach ($model->getErrors() as $error) {
                JFactory::getApplication()->enqueueMessage($error, 'error');
            }
            $msg = JText::_('GALLERY_TASK_CLASSIC_UPLOAD_ERROR');
        } else {
            $msg = JText::_('GALLERY_TASK_CLASSIC_UPLOAD_SUCCESS');
        }

        $this->setRedirect(JRoute::_('index.php?option=com_lovefactory&view=mygallery', false), $msg);
    }
}
