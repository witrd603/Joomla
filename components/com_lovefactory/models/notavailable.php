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

jimport('joomla.application.component.model');

class FrontendModelNotAvailable extends FactoryModel
{
    function getProfile()
    {
        $id = JFactory::getApplication()->input->getInt('id');

        $query = ' SELECT u.username'
            . ' FROM #__lovefactory_profiles p'
            . ' LEFT JOIN #__users u ON u.id = p.user_id'
            . ' WHERE p.user_id = ' . $id;
        $this->_db->setQuery($query);

        return $this->_db->loadObject();
    }
}
