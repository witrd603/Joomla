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

class FrontendModelModuleFriends extends FrontendModelModule
{
    protected $limitstart;
    protected $limit;

    public function __construct($config = array())
    {
        parent::__construct($config);

        $settings = LoveFactoryApplication::getInstance()->getSettings();

        $this->limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
        $this->limit = $settings->number_search_results_per_page;
    }

    public function getPage($page = 'profile_results', $mode = 'view')
    {
        $page = LoveFactoryPage::getInstance($page, $mode);

        return $page;
    }

    public function getCounters()
    {
        $counters = array();

        $model = JModelLegacy::getInstance('Friends', 'FrontendModel');
        $counters['requests'] = $model->getRequests();

        return $counters;
    }

    public function getItems()
    {
        $dbo = $this->getDbo();
        $query = $this->getQuery();

        $results = $dbo->setQuery($query, $this->limitstart, $this->limit)
            ->loadObjectList();

        return $results;
    }

    public function getPagination()
    {
        jimport('joomla.html.pagination');

        $total = $this->getTotal();
        $pagination = new JPagination($total, $this->limitstart, $this->limit);

        $pagination->setAdditionalUrlParam('format', 'html');

        return $pagination;
    }

    public function getSearch()
    {
        return JFactory::getApplication()->input->getString('search', '');
    }

    public function getOnline()
    {
        $online = JFactory::getApplication()->input->getString('online', 0);

        return $online ? 'checked="checked"' : '';
    }

    protected function getTotal($onlyTopFriends = false)
    {
        $dbo = $this->getDbo();
        $query = $this->getQuery($onlyTopFriends, true);

        $total = $dbo->setQuery($query)
            ->loadResult();

        return $total;
    }

    protected function getQuery($onlyTopFriends = false, $total = false)
    {
        // Initialise variables.
        $dbo = $this->getDbo();
        $search = JFactory::getApplication()->input->getString('search', '');
        $online = JFactory::getApplication()->input->getInt('online', 0);
        $mode = JFactory::getApplication()->input->getString('mode', '');
        $userId = JFactory::getUser()->id;

        // Get main query.
        $query = $dbo->getQuery(true)
            ->from('#__lovefactory_profiles p')
            ->where('f.id IS NOT NULL');

        // Select if users are friends.
        $query->leftJoin('#__lovefactory_friends f ON ((f.sender_id = p.user_id AND f.receiver_id = ' . $query->quote($userId) . ') OR (f.receiver_id = p.user_id AND f.sender_id = ' . $query->quote($userId) . ')) AND f.pending = ' . $query->quote(0) . ' AND f.type = ' . $query->quote(1));

        // Filter by search query.
        if ('' != $search) {
            $query->where('p.display_name LIKE ' . $query->quote('%' . $search . '%'));
        }

        // Filter by online.
        if (1 == $online) {
            $query->where('p.loggedin = ' . $query->quote(1));
        }

        if ($onlyTopFriends || 'top' == $mode) {
            //$query->where('IF (f.sender_id = ' . $query->quote($userId) . ', f.sender_status, f.receiver_status) = ' . $query->quote(1));
            $query->where('(CASE WHEN f.sender_id = ' . $query->quote($userId) . ' THEN f.sender_status ELSE f.receiver_status END) = ' . $query->quote(1));
        }

        if ($total) {
            $query->select('COUNT(p.user_id) AS total');
        } else {
            $query->select('p.*');

            // Select the membership.
            $query->select('m.title AS membership_title')
                ->leftJoin('#__lovefactory_memberships_sold s ON s.id = p.membership_sold_id')
                ->leftJoin('#__lovefactory_memberships m ON m.id = s.membership_id');

            // Select if user is blocked.
            $query->select('b.id AS blocked')
                ->leftJoin('#__lovefactory_blacklist b ON b.sender_id = ' . $query->quote($userId) . ' AND b.receiver_id = p.user_id');

            // Select if users are friends.
            $query->select('f.id AS is_friend, (CASE WHEN f.receiver_id = p.user_id THEN f.sender_status ELSE f.receiver_status END) AS is_top_friend');
        }

        return $query;
    }
}
