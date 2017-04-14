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

class FrontendControllerInteraction extends FrontendController
{
    public function send()
    {
        $model = $this->getModel('Interaction');
        $response = array();

        if ($model->send()) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('interaction_task_send_success');
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('interaction_task_send_error');
            $response['error'] = $model->getError();
        }

        $this->checkMembershipRestrictionRedirection($model, $response);

        $this->renderJson($response);

        return true;
    }

    public function respond()
    {
        $model = $this->getModel('Interaction');
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $response = array();

        $response['redirect'] = FactoryRoute::view('interactions');

        if ($model->respond($id)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('interaction_task_respond_success');
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('interaction_task_respond_error');
            $response['error'] = $model->getError();

            JFactory::getApplication()->enqueueMessage($response['error'], 'error');
        }

        $this->checkMembershipRestrictionRedirection($model, $response);

        $this->setRedirect($response['redirect'], $response['message']);

        return true;
    }
}
