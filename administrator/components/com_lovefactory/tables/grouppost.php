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

class TableGroupPost extends LoveFactoryTable
{
    public function __construct(&$db)
    {
        parent::__construct('#__lovefactory_group_posts', 'id', $db);
    }

    public function check()
    {
        if (!parent::check()) {
            return false;
        }

        // Check if the text is empty.
        if ('' == trim($this->text)) {
            $this->setError(FactoryText::_('grouppost_check_error_text_empty'));
            return false;
        }

        if (is_null($this->user_id)) {
            $this->user_id = JFactory::getUser()->id;
        }

        if (is_null($this->created_at)) {
            $this->created_at = JFactory::getDate()->toSql();
        }

        return true;
    }

    public function report()
    {
        $this->reported = 1;

        return $this->store();
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
}
