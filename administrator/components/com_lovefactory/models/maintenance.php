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

class BackendModelMaintenance extends JModelLegacy
{
    function __construct()
    {
        parent::__construct();
    }

    function cleanup()
    {
        // Messages
        $query = ' SELECT * '
            . ' FROM #__lovefactory_messages'
            . ' WHERE deleted_by_receiver = 1'
            . ' AND (deleted_by_sender = 1 OR sender_id = 0)';

        $this->_db->setQuery($query);
        $messages = $this->_db->loadObjectList();

        die();
    }
}
