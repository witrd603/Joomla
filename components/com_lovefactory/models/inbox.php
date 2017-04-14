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

class FrontendModelInbox extends LoveFactoryFrontendModelList
{
    public function getUnreadCount()
    {
        $user = JFactory::getUser();
        $dbo = $this->getDbo();

        if ($user->guest) {
            return false;
        }

        $query = $dbo->getQuery(true)
            ->select('COUNT(m.id)')
            ->from('#__lovefactory_messages m')
            ->where('m.receiver_id = ' . $dbo->quote($user->id))
            ->where('m.unread = ' . $dbo->quote(1))
            ->where('m.deleted_by_receiver = ' . $dbo->quote(0));

        // Filter by approved messages.
        $this->addQueryApprovalCondition($query);

        $result = $dbo->setQuery($query)
            ->loadResult();

        return $result;
    }

    public function getCounters()
    {
        $model = JModelLegacy::getInstance('Interactions', 'FrontendModel');
        $interactions = $model->getUnseen();

        return array(
            'inbox' => $this->getUnreadCount(),
            'interactions' => $interactions);
    }

    public function markAsRead($data)
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
            ->set('unread = ' . $dbo->quote(0))
            ->where('id IN (' . implode(',', $data) . ')')
            ->where('receiver_id = ' . $dbo->quote($user->id));

        return $dbo->setQuery($query)
            ->execute();
    }

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
            ->set('deleted_by_receiver = ' . $dbo->quote(1))
            ->where('id IN (' . implode(',', $data) . ')')
            ->where('receiver_id = ' . $dbo->quote($user->id));

        return $dbo->setQuery($query)
            ->execute();
    }

    public function getViewName()
    {
        return 'inbox';
    }

    public function getRestrictionMessage()
    {
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('message_access');
        $user = JFactory::getUser();

        try {
            $restriction->isAllowed($user->id);
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return false;
    }

    protected function getListQuery()
    {
        $user = JFactory::getUser();
        $query = parent::getListQuery();

        $query->select('m.*, m.sender_id AS user_id')
            ->from('#__lovefactory_messages m')
            ->where('m.receiver_id = ' . $query->quote($user->id))
            ->where('m.deleted_by_receiver = ' . $query->quote(0))
            ->order('m.date DESC');

        $query->select('p.display_name')
            ->leftJoin('#__lovefactory_profiles AS p ON p.user_id = m.sender_id');

        // Filter by approved messages.
        $this->addQueryApprovalCondition($query, false);

        return $query;
    }

    protected function addQueryApprovalCondition($query, $showOwn = true)
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if (!$settings->approval_messages) {
            return true;
        }

        $condition = 'm.approved = ' . $query->quote(1);

        if ($showOwn) {
            $condition = '(' . $condition . ' OR m.sender_id = ' . JFactory::getUser()->id . ')';
        }

        $query->where($condition);
    }
}
