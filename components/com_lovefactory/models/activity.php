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

class FrontendModelActivity extends LoveFactoryFrontendModelList
{
    public function __construct($config = array())
    {
        parent::__construct($config);

        $limit = LoveFactoryApplication::getInstance()->getSettings('wallpage_entries', 5);
        JFactory::getApplication()->input->set('limit', $limit);
    }

    public function getCounters()
    {
        $model = JModelLegacy::getInstance('Profile', 'FrontendModel');

        return $model->getCounters();
    }

    public function getProfile()
    {
        return LoveFactoryHelper::getUserProfileFromRequest();
    }

    protected function getListQuery()
    {
        $user_id = JFactory::getApplication()->input->getInt('user_id', JFactory::getUser()->id);
        $dbo = $this->getDbo();

        if (!$user_id) {
            $user_id = JFactory::getUser()->id;
        }

        $query = $dbo->getQuery(true)
            ->select('a.*')
            ->select('IF (' . $dbo->q($user_id) . ' = a.sender_id, ' . $dbo->q('sent') . ', ' . $dbo->q('received') . ') AS mode')
            ->select('p.display_name')
            ->from('#__lovefactory_activity a')
            ->leftJoin('#__lovefactory_profiles AS p ON p.user_id = IF (' . $dbo->q($user_id) . ' = a.sender_id, a.receiver_id, a.sender_id)')
            ->where('((a.sender_id = ' . $dbo->quote($user_id) . ' AND a.deleted_by_sender = 0) OR (a.receiver_id = ' . $dbo->quote($user_id) . ' AND a.deleted_by_receiver = 0))')
            ->order('a.created_at DESC');

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $array = array();

        foreach ($items as &$item) {
            $table = JTable::getInstance('Activity', 'Table');

            $table->bind($item);
            $table->params = new JRegistry($table->params);

            $array[] = $table;
        }

        return $array;
    }

    public function getMyWall()
    {
        // Initialise variables.
        $input = JFactory::getApplication()->input;
        $userId = $input->getInt('user_id', 0);
        $user = JFactory::getUser();

        // Check if user is guest.
        if ($user->guest) {
            return false;
        }

        // Check if user id is set in request,
        // if not then this is the current user's activity stream.
        if (!$userId) {
            return true;
        }

        // Check if current user id is the same with the user id from request.
        if ($userId == $user->id) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $user = JFactory::getUser();
        $table = $this->getTable('Activity', 'Table');

        $table->load($id);

        // Check if it's my action
        if ($table->sender_id != $user->id && $table->receiver_id != $user->id) {
            $this->setError(FactoryText::_('activity_task_delete_error_not_found'));
            return false;
        }

        if (!$table->softDelete($user->id)) {
            $this->setError($table->getError());
            return false;
        }

        return true;
    }

    public function getPagination()
    {
        $pagination = parent::getPagination();

        $pagination->setAdditionalUrlParam('option', 'com_lovefactory');
        $pagination->setAdditionalUrlParam('view', 'activity');

        return $pagination;
    }
}
