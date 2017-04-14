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

class FrontendControllerStatus extends FrontendController
{
    public function update()
    {
        $model = $this->getModel('status');
        $response = array();

        if ($model->update()) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('status_task_update_success');
            $response['error'] = '';
            $response['update'] = $model->getState('status');
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('status_task_update_error');
            $response['error'] = $model->getError();
        }

        $this->renderJson($response);
    }
}
