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

class FrontendControllerPrivacy extends FrontendController
{
    public function setPrivacy()
    {
        $batch = $this->input->get('batch', array(), 'array');
        $privacy = $this->input->getCmd('privacy');
        $type = $this->input->getCmd('type');
        $userId = JFactory::getUser()->id;
        $model = $this->getModel('Privacy', 'FrontendModel');
        $response = array();

        if ($model->setPrivacy($userId, $privacy, $type, $batch)) {
            $response['status'] = 1;
        } else {
            $response['status'] = 0;
            $response['message'] = $model->getError();
        }

        $updated = $model->getState('items.updated', array());
        $response['updated'] = $updated;

        $this->renderJson($response);

        return true;
    }
}
