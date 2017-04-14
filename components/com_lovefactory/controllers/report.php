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

class FrontendControllerReport extends FrontendController
{
    public function send()
    {
        $data = JFactory::getApplication()->input->get('data', array(), 'array');
        $model = $this->getModel('Report');
        $response = array();

        if ($model->send($data)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('report_task_send_success');
            $response['text'] = FactoryText::_('report_item_reported');
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('report_task_send_error');
            $response['error'] = $model->getError();
        }

        $this->renderJson($response);

        return true;
    }
}
