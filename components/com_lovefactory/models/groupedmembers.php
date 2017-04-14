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

class FrontendModelGroupedMembers extends LoveFactoryFrontendModelList
{
    protected $members;

    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->members = explode(',', JFactory::getApplication()->input->getString('members'));

        $settings = LoveFactoryApplication::getInstance()->getSettings();

        $this->limit = $settings->number_search_results_per_page;
        $this->limitstart = JFactory::getApplication()->input->getInt('limitstart');
    }

    public function getTotal()
    {
        return count($this->members);
    }

    public function getRendererResults()
    {
        $renderer = LoveFactoryPageRenderer::getInstance(array(
            'post_zone' => 'results/actions',
        ));

        return $renderer;
    }

    /**
     * @return LoveFactoryPage
     */
    public function getPageResults($page = 'profile_results', $mode = 'view')
    {
        $page = LoveFactoryPage::getInstance($page, $mode);

        return $page;
    }

    public function getColumns()
    {
        return LoveFactoryApplication::getInstance()->getSettings('results_columns', 1);
    }

    public function getAds()
    {
        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('a.*')
            ->from('#__lovefactory_adsense a');

        $results = $dbo->setQuery($query)
            ->loadObjectList();

        return $results;
    }

    protected function getListQuery()
    {
        $user = JFactory::getUser();
        $query = parent::getListQuery();

        $query->select('p.*')
            ->from('#__lovefactory_profiles p');
//      ->where('p.validated = ' . $query->quote(1));

        // Select username.
        $query->select('u.username')
            ->leftJoin('#__users u ON u.id = p.user_id');

        // Select if users are friends.
        $query->select('f.id AS is_friend')
            ->leftJoin('#__lovefactory_friends f ON ((f.sender_id = p.user_id AND f.receiver_id = ' . $query->quote($user->id) . ') OR (f.receiver_id = p.user_id AND f.sender_id = ' . $query->quote($user->id) . ')) AND f.pending = ' . $query->quote(0) . ' AND f.type = ' . $query->quote(1));

        $query->where('p.user_id IN (' . implode(',', $this->members) . ')');

        $this->addSearchConditions($query);

        foreach ($this->getPageResults()->getFields() as $field) {
            $field->addQueryView($query);
        }

        return $query;
    }

    protected function addSearchConditions($query)
    {
        // Initialise variables.
        $user = JFactory::getUser();
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        // Don't return own profile.
        $query->where('p.user_id <> ' . $query->quote($user->id));

        // Filter banned profiles.
        if ($settings->hide_banned_profiles) {
            $query->where('p.banned = ' . $query->quote(0));
        }

        // Filter blocked users.
        if ($settings->hide_ignored_profiles) {
            $query->leftJoin('#__lovefactory_blacklist b ON b.sender_id = ' . $query->quote($user->id) . ' AND b.receiver_id = p.user_id')
                ->where('b.id IS NULL');
        }

        // Filter Joomla blocked users.
        $query->where('u.block = ' . $query->quote(0));

        // Filter default membership users.
        if (!$settings->search_default_membership_show) {
            $query->where('p.membership_sold_id <> 1');
        }

        return $query;
    }

    protected function _getList($query, $limitstart = 0, $limit = 0)
    {
        JLoader::register('TableProfile', LoveFactoryApplication::getInstance()->getPath('component_administrator') . DS . 'tables' . DS . 'profile.php');

        $this->_db->setQuery($query, $this->limitstart, $this->limit);
        $result = array_values($this->_db->loadObjectList('user_id', 'TableProfile'));

        return $result;
    }

    public function getPagination()
    {
        $pagination = new LoveFactoryPagination($this->getTotal(), $this->limitstart, $this->limit);

        $pagination->setAdditionalUrlParam('option', 'com_lovefactory');
        $pagination->setAdditionalUrlParam('view', JFactory::getApplication()->input->getCmd('view', ''));

        return $pagination;
    }
}
