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

class TableGroupThread extends LoveFactoryTable
{
    public function __construct(&$db)
    {
        parent::__construct('#__lovefactory_group_threads', 'id', $db);
    }

    public function isMyGroup()
    {
        $table = JTable::getInstance('Group', 'Table');
        $table->load($this->group_id);

        return $table->isMyGroup();
    }

    public function check()
    {
        if (!parent::check()) {
            return false;
        }

        // Check if title and text are empty.
        if ('' == $this->title || '' == $this->text) {
            $this->setError(FactoryText::_('groupthread_check_error_title_or_text_empty'));
            return false;
        }

        if (is_null($this->created_at)) {
            $this->created_at = JFactory::getDate()->toSql();
        }

        if (is_null($this->user_id)) {
            $this->user_id = JFactory::getUser()->id;
        }

        return true;
    }

    public function isApproved()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if (!$settings->approval_group_threads || $this->approved) {
            return true;
        }

        $this->setError(FactoryText::_('groupthread_table_not_approved'));

        return false;
    }

    public function isOwner($userId = null)
    {
        if (is_null($userId)) {
            $userId = JFactory::getUser()->id;
        }

        return $userId == $this->user_id;
    }

    public function approve()
    {
        $this->approved = 1;

        return $this->store();
    }

    public function reject()
    {
        return $this->delete();
    }

    public function report()
    {
        $this->reported = 1;

        return $this->store();
    }

    public function getOwnerDisplayName()
    {
        $table = JTable::getInstance('Profile', 'Table');
        $table->load($this->user_id);

        return $table->display_name;
    }
}
