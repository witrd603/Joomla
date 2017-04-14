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

class BackendControllerGroupMember extends BackendController
{
    function __construct()
    {
        parent::__construct();
    }

    function remove()
    {
        $model = $this->getModel('GroupMember');

        if (!$model->delete()) {
            $msg = JText::_('Error Removing Group Member(s)!');
        } else {
            $msg = JText::_('Member(s) Removed!');
        }

        $this->setRedirect('index.php?option=com_lovefactory&task=groupmembers&id=' . $model->group_id, $msg);
    }
}
