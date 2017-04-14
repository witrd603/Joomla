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

class BackendControllerMembership extends BackendController
{
    function __construct()
    {
        parent::__construct();

        $this->registerTask('add', 'edit');
        $this->registerTask('apply', 'save');
    }

    function unpublish()
    {
        $model = $this->getModel('membership');

        if ($model->unpublish()) {
            $msg = JText::_('Membership(s) Unpublished!');
        } else {
            $msg = JText::_('Error Unpublishing Membership(s)!');
        }

        $this->setRedirect('index.php?option=com_lovefactory&task=memberships', $msg);
    }

    function publish()
    {
        $model = $this->getModel('membership');

        if ($model->publish()) {
            $msg = JText::_('Membership(s) Published!');
        } else {
            $msg = JText::_('Error Publishing Membership(s)');
        }

        $this->setRedirect('index.php?option=com_lovefactory&task=memberships', $msg);
    }

    function edit()
    {
        JFactory::getApplication()->input->set('view', 'membership');
        JFactory::getApplication()->input->set('layout', 'form');

        parent::display();
    }

    public function save()
    {
        $model = $this->getModel('membership');
        $data = $this->input->get('membership', array(), 'array');
        $id = $this->input->getInt('id');

        $data['id'] = $id;

        if ($model->save($data)) {
            $msg = JText::_('Membership Saved!');
        } else {
            $msg = JText::_('Error Saving Membership!');
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
        }

        if ($this->getTask() == 'apply') {
            $this->setRedirect('index.php?option=com_lovefactory&controller=membership&task=edit&cid[]=' . $model->getState('item.id'), $msg);
        } else {
            $this->setRedirect('index.php?option=com_lovefactory&task=memberships', $msg);
        }
    }

    function cancel()
    {
        $msg = JText::_('Operation Cancelled');
        $this->setRedirect('index.php?option=com_lovefactory&task=memberships', $msg);
    }

    function remove()
    {
        $model = $this->getModel('membership');

        if (!$model->delete()) {
            $msg = JText::_('Error: One or More Memberships Could not be Deleted!') . ' ' . $model->getError();
        } else {
            $msg = JText::_('Membership(s) Deleted!');
        }

        $this->setRedirect('index.php?option=com_lovefactory&task=memberships', $msg);
    }

    function saveorder()
    {
        $model = $this->getModel('membership');

        if ($model->saveorder()) {
            $msg = JText::_('Order Saved!');
        } else {
            $msg = JText::_('Error Saving Order');
        }

        $this->setRedirect('index.php?option=com_lovefactory&task=memberships', $msg);
    }

    function orderup()
    {
        $model = $this->getModel('membership');

        $model->orderContent(-1);

        $this->setRedirect('index.php?option=com_lovefactory&task=memberships');
    }

    function orderdown()
    {
        $model = $this->getModel('membership');

        $model->orderContent(1);

        $this->setRedirect('index.php?option=com_lovefactory&task=memberships');
    }

    function setDefault()
    {
        $model = $this->getModel('membership');

        if ($model->setDefault()) {
            $msg = JText::_('Default Membership Set!');
        } else {
            $msg = JText::_('Error Setting Default Membership!');
        }

        $this->setRedirect('index.php?option=com_lovefactory&task=memberships', $msg);
    }

    function moveUsers()
    {
        $model = $this->getModel('Memberships');
        $view = $this->getView('Memberships', 'html');

        $view->setModel($model, true);
        $view->moveForm();
    }

    function doMoveUsers()
    {
        $model = $this->getModel('memberships');

        if ($model->moveUsers()) {
            $msg = JText::_('Users Moved!');
        } else {
            $msg = JText::_('Error Moving Users!');
        }

        $this->setRedirect('index.php?option=com_lovefactory&task=memberships', $msg);
    }

    function pricing()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');

        $mainframe->redirect('index.php?option=com_lovefactory&task=pricing');
    }

    function saveOptions()
    {
    }
}
