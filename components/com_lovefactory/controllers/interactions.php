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

class FrontendControllerInteractions extends FrontendController
{
    public function delete()
    {
        $model = $this->getModel('Interactions');
        $data = JFactory::getApplication()->input->get('batch', array(), 'array');

        if ($model->delete($data)) {
            $msg = FactoryText::_('interactions_task_delete_success');
        } else {
            $msg = FactoryText::_('interactions_task_delete_error');
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
        }

        $this->setRedirect(FactoryRoute::view('interactions'), $msg);

        return true;
    }
}
