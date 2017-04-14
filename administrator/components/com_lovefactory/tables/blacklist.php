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

class TableBlacklist extends JTable
{
    var $id = null;
    var $receiver_id = null;
    var $sender_id = null;
    var $date = null;

    function __construct(&$db)
    {
        parent::__construct('#__lovefactory_blacklist', 'id', $db);
    }
}
