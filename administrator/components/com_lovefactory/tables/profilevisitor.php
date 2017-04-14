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

class TableProfileVisitor extends JTable
{
    var $id = null;
    var $user_id = null;
    var $visitor_id = null;
    var $date = null;

    function __construct(&$db)
    {
        parent::__construct('#__lovefactory_profile_visitors', 'id', $db);
    }

    function update($profile_id, $user_id)
    {
        if ($profile_id == $user_id || $user_id == 0) {
            return false;
        }

        $date = JFactory::getDate();

        $this->user_id = $profile_id;
        $this->visitor_id = $user_id;
        $this->date = $date->toSql();

        $this->store();
    }
}
