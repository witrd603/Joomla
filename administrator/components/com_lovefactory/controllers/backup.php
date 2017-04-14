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

class BackendControllerBackup extends BackendController
{
    function __construct()
    {
        parent::__construct();
    }

    function create()
    {
        $mainframe = JFactory::getApplication();

        $model = $this->getModel('backup');

        if ($model->create()) {
            $msg = JText::_('Backup File Created Successfully!');
        } else {
            $msg = JText::_('Error Creating Backup File!') . ' ' . $model->getError();
        }

        $mainframe->redirect('index.php?option=com_lovefactory&task=backup', $msg);
    }

    function restore()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');

        $model = $this->getModel('backup');

        if ($model->restore()) {
            $msg = JText::_('Backup File Restored Successfully!');
        } else {
            $msg = JText::_('Error Restoring Backup File!') . ' ' . $model->getError();
        }

        $mainframe->redirect('index.php?option=com_lovefactory&view=settings&layout=backup', $msg);
    }
}
