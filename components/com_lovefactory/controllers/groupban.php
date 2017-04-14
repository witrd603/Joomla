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

class FrontendControllerGroupBan extends FrontendController
{
    public function ban()
    {
        $input = JFactory::getApplication()->input;
        $userId = $input->getInt('user_id', 0);
        $groupId = $input->getInt('group_id', 0);
        $model = $this->getModel('GroupBanned');

        if ($model->ban($userId, $groupId)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('groupban_task_ban_success');
            $response['text'] = FactoryText::_('group_banned_user');
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('groupban_task_ban_error');
            $response['error'] = $model->getError();
        }

        $this->renderJson($response);

        return false;
    }
}
