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

class FrontendModelInteractions extends LoveFactoryFrontendModelList
{
    public function getCounters()
    {
        $model = JModelLegacy::getInstance('Inbox', 'FrontendModel');

        return $model->getCounters();
    }

    public function getItems()
    {
        $items = parent::getItems();

        $this->markAsSeen($items);

        return $items;
    }

    public function getUnseen()
    {
        $user = JFactory::getUser();

        if ($user->guest) {
            return false;
        }

        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('COUNT(id)')
            ->from('#__lovefactory_interactions i')
            ->where('i.receiver_id = ' . $dbo->quote($user->id))
            ->where('i.seen = ' . $dbo->quote(0));

        $result = $dbo->setQuery($query)
            ->loadResult();

        return $result;
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
            ->update('#__lovefactory_interactions')
            ->set('deleted_by_sender = (CASE WHEN sender_id = ' . $dbo->quote($user->id) . ' THEN 1 ELSE deleted_by_sender END)')
            ->set('deleted_by_receiver = (CASE WHEN receiver_id = ' . $dbo->quote($user->id) . ' THEN 1 ELSE deleted_by_receiver END)')
            ->where('id IN (' . implode(',', $data) . ')')
            ->where('(sender_id = ' . $dbo->quote($user->id) . ' OR receiver_id = ' . $dbo->quote($user->id) . ')');

        return $dbo->setQuery($query)
            ->execute();
    }

    protected function getListQuery()
    {
        $query = parent::getListQuery();
        $user = JFactory::getUser();

        $query->select('i.*')
            ->select('(CASE WHEN i.sender_id = ' . $query->quote($user->id) . ' THEN ' . $query->quote('sent') . ' ELSE ' . $query->quote('received') . ' END) AS status')
            ->from('#__lovefactory_interactions i')
            ->where('((i.sender_id = ' . $query->quote($user->id) . ' AND i.deleted_by_sender = ' . $query->quote(0) . ') OR (i.receiver_id = ' . $query->quote($user->id) . ' AND i.deleted_by_receiver = ' . $query->quote(0) . '))')
            ->order('i.date DESC');

        $query->select('p.display_name, p.user_id')
            ->leftJoin('#__lovefactory_profiles AS p ON p.user_id = (CASE WHEN i.sender_id = ' . $query->quote($user->id) . ' THEN i.receiver_id ELSE i.sender_id END)');

        return $query;
    }

    protected function markAsSeen($items)
    {
        $array = array();

        foreach ($items as $item) {
            if ('received' == $item->status && !$item->seen) {
                $array[] = $item->id;
            }
        }

        if (!$array) {
            return true;
        }

        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->update('#__lovefactory_interactions')
            ->set('seen = ' . $dbo->quote(1))
            ->where('id IN (' . implode(',', $array) . ')');

        return $dbo->setQuery($query)
            ->execute();
    }
}
