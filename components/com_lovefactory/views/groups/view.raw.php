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

jimport('joomla.application.component.view');

class FrontendViewGroups extends JFactoryView
{
    function display($tpl = 'list')
    {
        $Itemid = JFactory::getApplication()->input->get('Itemid');

        $groups = $this->get('Data');
        $pagination = $this->get('Pagination');

        $this->assignRef('groups', $groups);
        $this->assignRef('pagination', $pagination);
        $this->assignRef('Itemid', $Itemid);

        parent::display($tpl);
    }
}
