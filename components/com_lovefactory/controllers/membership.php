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

class FrontendControllerMembership extends FrontendController
{
    public function trial()
    {
        $model = $this->getModel('Membership');

        if ($model->trial()) {
            $msg = FactoryText::_('membership_task_trial_success');
        } else {
            $msg = FactoryText::_('membership_task_trial_error');
        }

        $this->setRedirect(JRoute::_('index.php?option=com_lovefactory&view=memberships'), $msg);
    }

    public function free()
    {
        $model = $this->getModel('Membership');

        if ($model->free()) {
            $msg = FactoryText::_('membership_task_free_success');
        } else {
            $msg = FactoryText::_('membership_task_free_error');
        }

        $this->setRedirect(JRoute::_('index.php?option=com_lovefactory&view=memberships'), $msg);
    }
}
