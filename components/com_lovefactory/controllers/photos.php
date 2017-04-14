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

class FrontendControllerPhotos extends FrontendController
{
    public function saveOrder()
    {
        $data = JFactory::getApplication()->input->post->get('photo', array(), 'array');
        $model = $this->getModel('Photos');
        $response = array();

        if ($model->saveOrder($data)) {
            $response['status'] = 1;
        } else {
            $response['status'] = 0;
        }

        $this->renderJson($response);

        return true;
    }

    public function delete()
    {
        $batch = JFactory::getApplication()->input->get('batch', array(), 'array');
        $model = $this->getModel('Photo');
        $response = array();

        if ($model->delete($batch)) {
            $response['status'] = 1;
        } else {
            $response['status'] = 0;
        }

        $response['removed'] = $model->getState('removed', array());

        $this->renderJson($response);

        return true;
    }
}
