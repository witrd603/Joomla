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

class TableApproval extends JTable
{
    public $id = null;
    public $type = null;
    public $item_id = null;
    public $user_id = null;
    public $message = null;
    public $approved = null;
    public $created_at = null;

    public function __construct(&$db)
    {
        parent::__construct('#__lovefactory_approvals', 'id', $db);
    }

    public function store($updateNulls = false)
    {
        if (!$this->id) {
            $this->created_at = JFactory::getDate()->toSql();
        }

        return parent::store($updateNulls);
    }
}
