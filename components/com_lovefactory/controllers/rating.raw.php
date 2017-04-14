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

class FrontendControllerRating extends FrontendController
{
    public function add()
    {
        $model = $this->getModel('Rating');
        $response = array();

        if ($model->add()) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('rating_task_add_success');
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('rating_task_add_error');
            $response['error'] = $model->getError();
        }

        $this->renderJson($response);
    }
}
