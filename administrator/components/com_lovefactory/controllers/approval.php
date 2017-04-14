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

class BackendControllerApproval extends BackendController
{
    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->registerTask('reject', 'approve');
    }

    public function approve()
    {
        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');
        $model = $this->getModel('Approval');
        $task = $this->getTask();
        $message = JFactory::getApplication()->input->getString('reject_reason');

        if ($model->approve($cid, $task, $message)) {
            $msg = JText::_('APPROVAL_' . strtoupper($task) . '_SUCCESS');
        } else {
            $msg = JText::_('APPROVAL_' . strtoupper($task) . '_ERROR');
            throw new Exception($model->getEror(), 500);
        }

        $this->setRedirect('index.php?option=com_lovefactory&view=approvals', $msg);

        $next = JFactory::getApplication()->input->getInt('nextitem');

        if ($next) {
            JModelLegacy::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'models', 'BackendModel');
            $model = $this->getModel('Approvals', 'BackendModel');
            $data = $model->getData();

            if (is_array($data) && isset($data[0])) {
                $this->setRedirect('index.php?option=com_lovefactory&controller=approval&task=review&cid[]=' . $data[0]->type . '.' . $data[0]->item_id, $msg);
            }
        }
    }

    public function review()
    {
        JFactory::getApplication()->input->set('view', 'approval');
        //JRequest::setVar('hidemainmenu', 1);

        parent::display();
    }

    public function cancel()
    {
        $this->setRedirect('index.php?option=com_lovefactory&view=approvals', JText::_('APPROVAL_CANCEL'));
    }
}
