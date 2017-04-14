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

class FrontendControllerActivity extends FrontendController
{
    public function delete()
    {
        $model = $this->getModel('activity');
        $response = array();

        if ($model->delete()) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('activtiy_task_delete_success');
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('activtiy_task_delete_error');
            $response['error'] = $model->getError();
        }

        $this->renderJson($response);
    }
}
