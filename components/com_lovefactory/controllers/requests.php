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

class FrontendControllerRequests extends FrontendController
{
    function filter()
    {
        $session = JFactory::getSession();
        $my_requests = JFactory::getApplication()->input->getInt('my_requests');

        $session->set('lovefactory.requests.filter.my_requests', $my_requests);

        $Itemid = JFactory::getApplication()->input->getInt('Itemid');
        $mainframe = JFactory::getApplication();

        $mainframe->redirect(JRoute::_('index.php?option=com_lovefactory&view=requests&Itemid=' . $Itemid, false));
    }
}
