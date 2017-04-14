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

class FrontendModelBlocked extends LoveFactoryFrontendModelList
{
    public function getCounters()
    {
        $model = JModelLegacy::getInstance('MyFriends', 'FrontendModel');

        return $model->getCounters();
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

    protected function getListQuery()
    {
        $user = JFactory::getUser();
        $query = parent::getListQuery();

        $query->select('b.receiver_id AS user_id, b.date, p.display_name')
            ->from('#__lovefactory_blacklist b')
            ->leftJoin('#__lovefactory_profiles AS p ON p.user_id = b.receiver_id')
            ->where('b.sender_id = ' . $query->quote($user->id))
            ->order('b.date DESC');

        return $query;
    }
}
