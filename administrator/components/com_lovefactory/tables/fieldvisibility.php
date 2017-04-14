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

class TableFieldVisibility extends JTable
{
    var $id = null;
    var $field_id = null;
    var $user_id = null;
    var $visibility = null;

    function __construct(&$db)
    {
        parent::__construct('#__lovefactory_fields_visibility', 'id', $db);
    }
}
