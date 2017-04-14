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

class FrontendModelRequests extends LoveFactoryFrontendModelList
{
    public function getRenderer()
    {
        $renderer = LoveFactoryPageRenderer::getInstance(array(
            'post_zone' => 'requests/actions'
        ));

        return $renderer;
    }

    public function getPage($page = 'profile_results', $mode = 'view')
    {
        $page = LoveFactoryPage::getInstance($page, $mode);

        return $page;
    }

    public function getCounters()
    {
        $model = JModelLegacy::getInstance('MyFriends', 'FrontendModel');

        return $model->getCounters();
    }

    public function getSettings()
    {
        return LoveFactoryApplication::getInstance()->getSettings();
    }

    protected function getListQuery($userId = null)
    {
        $query = parent::getListQuery();

        if (is_null($userId)) {
            $userId = JFactory::getApplication()->input->getInt('user_id', JFactory::getUser()->id);
        }

        $query->select('f.message AS request_message, f.type AS request_type, 0 AS is_friend, f.approved')
            ->select('(CASE WHEN f.sender_id = ' . $query->quote($userId) . ' THEN 1 WHEN f.receiver_id = ' . $query->quote($userId) . ' THEN 0 END) AS ismyrequest')
            ->from('#__lovefactory_friends f')
            ->where('(f.sender_id = ' . $query->quote($userId) . ' OR f.receiver_id = ' . $query->quote($userId) . ')')
            ->where('f.pending = ' . $query->quote(1));

        // Select profile.
        $query->select('p.*')
            ->leftJoin('#__lovefactory_profiles p ON p.user_id = (CASE WHEN f.sender_id = ' . $query->quote($userId) . ' THEN f.receiver_id WHEN f.receiver_id = ' . $query->quote($userId) . ' THEN f.sender_id END)');

        // Select the username.
        $query->select('u.username')
            ->leftJoin('#__users u ON u.id = p.user_id');

        return $query;
    }

    protected function _getList($query, $limitstart = 0, $limit = 0)
    {
        $this->_db->setQuery($query, $limitstart, $limit);
        $result = $this->_db->loadObjectList('user_id', 'TableProfile');

        return $result;
    }
}
