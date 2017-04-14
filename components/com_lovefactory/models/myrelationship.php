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

jimport('joomla.application.component.model');

class FrontendModelMyRelationship extends FactoryModel
{
    public function getPage($page = 'profile_results', $mode = 'view')
    {
        $page = LoveFactoryPage::getInstance($page, $mode);

        return $page;
    }

    public function getItem()
    {
        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true);
        $userId = JFactory::getUser()->id;

        $query->select('f.id AS is_friend')
            ->from('#__lovefactory_friends f')
            ->where('(f.sender_id = ' . $dbo->quote($userId) . ' OR f.receiver_id = ' . $dbo->quote($userId) . ')')
            ->where('f.type = ' . $dbo->quote(2))
            ->where('f.pending = ' . $dbo->quote(0));

        // Select the profile.
        $query->select('p.*')
            ->leftJoin('#__lovefactory_profiles p ON p.user_id = (CASE WHEN f.sender_id = ' . $dbo->quote($userId) . ' THEN f.receiver_id ELSE f.sender_id END)');

        // Select the username.
        $query->select('u.username')
            ->leftJoin('#__users u ON u.id = p.user_id');

        // Select the membership.
        $query->select('m.title AS membership_title')
            ->leftJoin('#__lovefactory_memberships_sold s ON s.id = p.membership_sold_id')
            ->leftJoin('#__lovefactory_memberships m ON m.id = s.membership_id');

        // Select if user is blocked.
        $query->select('b.id AS blocked')
            ->leftJoin('#__lovefactory_blacklist b ON b.sender_id = ' . $query->quote($userId) . ' AND b.receiver_id = p.user_id');

        JLoader::register('TableProfile', LoveFactoryApplication::getInstance()->getPath('component_administrator') . DS . 'tables' . DS . 'profile.php');

        $result = $dbo->setQuery($query)
            ->loadObject('TableProfile');

        return $result;
    }

    public function getCounters()
    {
        $model = JModelLegacy::getInstance('MyFriends', 'FrontendModel');

        return $model->getCounters();
    }
}
