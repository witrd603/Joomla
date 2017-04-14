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

class TableGroupMember extends JTable
{
    public function __construct(&$db)
    {
        parent::__construct('#__lovefactory_group_members', 'id', $db);
    }

    public function check()
    {
        if (!parent::check()) {
            return false;
        }

        if (is_null($this->created_at)) {
            $this->created_at = JFactory::getDate()->toSql();
        }

        return true;
    }

    public function registerActivity($groupTitle)
    {
        $activity = JTable::getInstance('Activity', 'Table');

        return $activity->register(
            'group_join',
            $this->user_id,
            $this->user_id,
            $this->group_id,
            array(
                'title' => $groupTitle
            )
        );
    }
}
