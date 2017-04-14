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

jimport('joomla.application.component.modellist');

class FrontendModelOutbox extends LoveFactoryFrontendModelList
{
    public function delete($data)
    {
        $user = JFactory::getUser();
        JArrayHelper::toInteger($data);

        if (!$data) {
            $this->setError(FactoryText::_('inbox_task_mark_error_list_empty'));
            return false;
        }

        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->update('#__lovefactory_messages')
            ->set('deleted_by_sender = ' . $dbo->quote(1))
            ->where('id IN (' . implode(',', $data) . ')')
            ->where('sender_id = ' . $dbo->quote($user->id));

        return $dbo->setQuery($query)
            ->execute();
    }

    public function getApproval()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        return $settings->approval_messages;
    }

    public function getViewName()
    {
        return 'outbox';
    }

    protected function getListQuery()
    {
        $user = JFactory::getUser();
        $query = parent::getListQuery();

        $query->select('m.*, m.receiver_id AS user_id')
            ->from('#__lovefactory_messages m')
            ->where('m.sender_id = ' . $query->quote($user->id))
            ->where('m.deleted_by_sender = ' . $query->quote(0))
            ->order('m.date DESC');

        $query->select('p.display_name')
            ->leftJoin('#__lovefactory_profiles AS p ON p.user_id = m.receiver_id');

        // Filter by approved messages.
        $this->addQueryApprovalCondition($query);

        return $query;
    }

    protected function addQueryApprovalCondition($query, $showOwn = true)
    {
        if (!$this->getApproval()) {
            return true;
        }

        $condition = 'm.approved = ' . $query->quote(1);

        if ($showOwn) {
            $condition = '(' . $condition . ' OR m.sender_id = ' . JFactory::getUser()->id . ')';
        }

        $query->where($condition);
    }
}
