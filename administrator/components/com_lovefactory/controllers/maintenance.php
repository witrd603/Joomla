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

class BackendControllerMaintenance extends BackendController
{
    function __construct()
    {
        parent::__construct();
    }

    function cleanup()
    {
        $model = $this->getModel('maintenance');

        if ($model->cleanup()) {
            $msg = JText::_('Cleanup Completed!');
        } else {
            $msg = JText::_('Error Completing Cleanup');
        }

        $this->setRedirect('index.php?option=com_lovefactory&task=settings', $msg);
    }
}
