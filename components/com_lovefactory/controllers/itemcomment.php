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

class FrontendControllerItemComment extends FrontendController
{
    public function add()
    {
        $model = $this->getModel('ItemComment');
        $user = JFactory::getUser();
        $data = JFactory::getApplication()->input->get('data', array(), 'array');
        $response = array();

        $data['user_id'] = $user->id;

        if ($model->save($data)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('itemcomment_task_add_success');
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('itemcomment_task_add_error');
            $response['error'] = $model->getError();
        }

        $this->checkMembershipRestrictionRedirection($model, $response);

        $this->renderJson($response);

        return true;
    }

    public function delete()
    {
        $model = $this->getModel('ItemComment');
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $response = array();

        if ($model->delete($id)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('itemcomment_task_delete_success');
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('itemcomment_task_delete_error');
            $response['error'] = $model->getError();
        }

        $this->renderJson($response);

        return true;
    }

    public function report()
    {
        $model = $this->getModel('ItemComment');
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $response = array();

        if ($model->report($id)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('itemcomment_task_report_success');
            $response['text'] = FactoryText::_('itemcomment_reported');
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('itemcomment_task_report_error');
            $response['error'] = $model->getError();
        }

        $this->renderJson($response);

        return true;
    }
}
