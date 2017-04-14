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

class BackendControllerPrice extends BackendController
{
    function __construct()
    {
        parent::__construct();

        $this->registerTask('add', 'edit');
        $this->registerTask('apply', 'save');
    }

    function edit()
    {
        JFactory::getApplication()->input->set('view', 'price');
        JFactory::getApplication()->input->set('layout', 'form');

        parent::display();
    }

    function save()
    {
        $model = $this->getModel('price');

        if ($model->store()) {
            $msg = JText::_('Price Saved!');
        } else {
            $msg = JText::_('Error Saving Price!');
        }

        if ('apply' == $this->getTask()) {
            $this->setRedirect('index.php?option=com_lovefactory&controller=price&task=edit&cid[]=' . $model->_id, $msg);
        } else {
            $this->setRedirect('index.php?option=com_lovefactory&task=pricing', $msg);
        }
    }

    function remove()
    {
        $model = $this->getModel('price');

        if (!$model->delete()) {
            $msg = JText::_('Error: One or More Prices Could not be Deleted');
        } else {
            $msg = JText::_('Price(s) Deleted');
        }

        $this->setRedirect('index.php?option=com_lovefactory&task=pricing', $msg);
    }

    function cancel()
    {
        $msg = JText::_('Operation Cancelled');
        $this->setRedirect('index.php?option=com_lovefactory&task=pricing', $msg);
    }

    function unpublish()
    {
        $model = $this->getModel('price');

        if ($model->unpublish()) {
            $msg = JText::_('Price(s) Unpublished!');
        } else {
            $msg = JText::_('Error Unpublishing Price(s)');
        }

        $this->setRedirect('index.php?option=com_lovefactory&task=pricing', $msg);
    }

    function publish()
    {
        $model = $this->getModel('price');

        if ($model->publish()) {
            $msg = JText::_('Price(s) Published!');
        } else {
            $msg = JText::_('Error Publishing Price(s)');
        }

        $this->setRedirect('index.php?option=com_lovefactory&task=pricing', $msg);
    }
}
