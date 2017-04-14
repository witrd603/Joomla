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

class FrontendModelFriends extends LoveFactoryFrontendModelList
{
    public function getPage($page = 'friends_view', $mode = 'view')
    {
        $page = LoveFactoryPage::getInstance($page, $mode);

        return $page;
    }

    public function getRequests()
    {
        $user = JFactory::getUser();
        $dbo = $this->getDbo();

        if ($user->guest) {
            return false;
        }

        $query = $dbo->getQuery(true)
            ->select('COUNT(1) AS requests')
            ->from('#__lovefactory_friends f')
            ->where('f.receiver_id = ' . $dbo->quote($user->id))
            ->where('f.pending = ' . $dbo->quote(1));

        return $dbo->setQuery($query)
            ->loadResult();
    }

    public function getCounters()
    {
        $model = JModelLegacy::getInstance('Profile', 'FrontendModel');

        return $model->getCounters();
    }

    protected function getListQuery($userId = null)
    {
        $query = parent::getListQuery();

        if (is_null($userId)) {
            $userId = JFactory::getApplication()->input->getInt('user_id', JFactory::getUser()->id);
        }

        $query->select('p.*')
            ->from('#__lovefactory_profiles p')
            ->where('f.id IS NOT NULL');

        // Select the username.
        $query->select('u.username')
            ->leftJoin('#__users u ON u.id = p.user_id');

        // Select if users are friends.
        $query->select('f.id AS is_friend')
            ->leftJoin('#__lovefactory_friends f ON ((f.sender_id = p.user_id AND f.receiver_id = ' . $query->quote($userId) . ') OR (f.receiver_id = p.user_id AND f.sender_id = ' . $query->quote($userId) . ')) AND f.pending = ' . $query->quote(0));

        // Select the membership.
        $query->select('m.title AS membership_title')
            ->leftJoin('#__lovefactory_memberships_sold s ON s.id = p.membership_sold_id')
            ->leftJoin('#__lovefactory_memberships m ON m.id = s.membership_id');

        // Select if user is blocked.
        $query->select('b.id AS blocked')
            ->leftJoin('#__lovefactory_blacklist b ON b.sender_id = ' . $query->quote($userId) . ' AND b.receiver_id = p.user_id');

        return $query;
    }

    protected function _getList($query, $limitstart = 0, $limit = 0)
    {
        $this->_db->setQuery($query, $limitstart, $limit);
        $results = $this->_db->loadObjectList('user_id');

        $array = array();

        foreach ($results as $id => $result) {
            $table = JTable::getInstance('Profile', 'Table');
            $table->bind($result);

            $array[$id] = $table;
        }

        return $array;
    }
}
