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

jimport('joomla.application.component.controllerform');

class BackendControllerGroup extends JControllerForm
{
    protected $option = 'com_lovefactory';

    public function remove()
    {
        $cid = $this->input->get('cid', array(), 'array');
        $model = $this->getModel('Group');

        JArrayHelper::toInteger($cid);

        if ($model->delete($cid)) {
            $msg = FactoryText::_('group_task_delete_success');
        } else {
            $msg = FactoryText::_('group_task_delete_error');
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
        }

        $this->setRedirect(FactoryRoute::_('view=groups'), $msg);

        return true;
    }
}
