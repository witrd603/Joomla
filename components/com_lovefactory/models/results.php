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

class FrontendModelResults extends LoveFactoryFrontendModelList
{
    protected $fields = array();
    protected $request;
    protected $limitedResults = false;
    protected $online = false;

    public function __construct($config = array())
    {
        parent::__construct($config);

        if (isset($config['fields'])) {
            $this->fields = $config['fields'];
        }

        if (isset($config['request'])) {
            $this->request = $config['request'];
        }

        if (isset($config['online'])) {
            $this->online = $config['online'];
        }

        $settings = LoveFactoryApplication::getInstance()->getSettings();
        JFactory::getApplication()->input->set('limit', $settings->number_search_results_per_page);

        $this->getState();

        if ($settings->limit_search_results && ($this->getState('list.limit') + $this->getState('list.start') > $settings->limit_search_results)) {
            JFactory::getApplication()->input->set('limitstart', 0);
        }

        $this->filterOrder = array(
            (1 == $settings->results_default_sort_by ? '' : 'username') => array('column' => 'u.username', 'text' => FactoryText::_('results_filter_username')),
            (4 == $settings->results_default_sort_by ? '' : 'rating') => array('column' => 'p.rating', 'text' => FactoryText::_('results_filter_rating')),
            (5 == $settings->results_default_sort_by ? '' : 'lastseen') => array('column' => 'p.lastvisit', 'text' => FactoryText::_('results_filter_lastseen')),
        );
        $this->filterDir = array(
            (1 == $settings->results_default_sort_order ? '' : 'asc') => array('dir' => 'asc', 'text' => FactoryText::_('results_filter_dir_asc')),
            (0 == $settings->results_default_sort_order ? '' : 'desc') => array('dir' => 'desc', 'text' => FactoryText::_('results_filter_dir_desc')),
        );
    }

    public function getTotal()
    {
        $total = parent::getTotal();
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if ($settings->limit_search_results && $settings->limit_search_results < $total) {
            $total = $settings->limit_search_results;
            $this->limitedResults = $total;
        }

        return $total;
    }

    /**
     * @param string $page
     * @param string $mode
     * @return LoveFactoryPage
     */
    public function getPageResults($page = 'profile_results', $mode = 'view')
    {
        $page = LoveFactoryPage::getInstance($page, $mode);

        return $page;
    }

    public function getLimitedResults()
    {
        return $this->limitedResults;
    }

    public function getFilter()
    {
        if ('fixedsearch' === JFactory::getApplication()->input->getCmd('view')) {
            return null;
        }

        $default = $this->getRequestFilterOrder();

        $options = array();
        foreach ($this->filterOrder as $text => $value) {
            $options[$text] = $value['text'];
        }

        return JHtml::_('select.genericlist', $options, 'filter[sort]', '', '', '', $default, 'filter_order');
    }

    public function getFilterDir()
    {
        $default = $this->getRequestFilterDir();

        $options = array();
        foreach ($this->filterDir as $text => $value) {
            $options[$text] = $value['text'];
        }

        return JHtml::_('select.genericlist', $options, 'filter[order]', '', '', '', $default, 'filter_order_Dir');
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

        $this->addSearchConditions($query);
        $this->addOrder($query);

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

        foreach ($this->fields as $field) {
            $field->bind($this->request);
            $field->addQuerySearchCondition($query);
        }

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

        // Check if we're on the online users page.
        if ($this->online) {
            $field = LoveFactoryField::getInstance('Online');
            $field->bindValue(array(1));

            $query->where($field->getQuerySearchCondition($query));
        }

        // Filter out private or only for friends profiles.
        $query->where('((p.online = ' . $query->q(0) . ') OR (p.online = ' . $query->q(1) . ' AND f.id IS NOT NULL))');

        $helper = new ThePhpFactory\LoveFactory\Helper\OppositeGender($settings);
        if ($helper->isOppositeGenderSearchEnabled($user)) {
            $helper->addOppositeGenderSearchCondition($query, $user);
        }

        return $query;
    }

    protected function _getList($query, $limitstart = 0, $limit = 0)
    {
        JLoader::register('TableProfile', LoveFactoryApplication::getInstance()->getPath('component_administrator') . DS . 'tables' . DS . 'profile.php');

//    echo $query->dump();

        $this->_db->setQuery($query, $limitstart, $limit);

        $results = $this->_db->loadObjectList();
        $result = array();

        foreach ($results as $temp) {
            $table = JTable::getInstance('Profile', 'Table');
            $table->bind($temp);

            $result[] = $table;
        }

        return $result;
    }

    protected function getBirthdateField()
    {
        $query = ' SELECT id'
            . ' FROM #__lovefactory_fields'
            . ' WHERE type = "Birthdate"'
            . ' LIMIT 0,1';
        $this->_db->setQuery($query);

        $field = $this->_db->loadResult();

        return !is_null($field) ? 'p.field_' . $field : false;
    }

    protected function addOrder($query)
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if ($settings->sort_by_membership) {
            $query->leftJoin('#__lovefactory_memberships_sold s ON s.id = p.membership_sold_id')
                ->leftJoin('#__lovefactory_memberships m ON m.id = s.membership_id')
                ->order('m.ordering DESC');
        }

        $order = $this->getRequestFilterOrder();
        $dir = $this->getRequestFilterDir();

        $orderSet = $this->filterOrder[$order]['column'];
        $dirSet = $this->filterDir[$dir]['dir'];

        $query->order($query->escape($orderSet) . ' ' . $query->escape($dirSet));
    }

    protected function getRequestFilterOrder()
    {
        $filter = JFactory::getApplication()->input->get('filter', array(), 'array');

        return isset($filter['sort']) ? $filter['sort'] : '';
    }

    protected function getRequestFilterDir()
    {
        $filter = JFactory::getApplication()->input->get('filter', array(), 'array');

        return isset($filter['order']) ? $filter['order'] : '';
    }

    public function getPagination()
    {
        $limit = (int)$this->getState('list.limit') - (int)$this->getState('list.links');
        $pagination = new LoveFactoryPagination($this->getTotal(), $this->getStart(), $limit);

        $pagination->setAnchor('results');

        $pagination->setAdditionalUrlParam('option', 'com_lovefactory');
        $pagination->setAdditionalUrlParam('view', JFactory::getApplication()->input->getCmd('view', ''));

        if ($this->request && JFactory::getApplication()->getRouter()->getMode()) {
            foreach ($this->request as $id => $value) {
                if (!is_array($value)) {
                    $pagination->setAdditionalUrlParam('form[' . $id . ']', $value);
                } else {
                    foreach ($value as $key => $val) {
                        $pagination->setAdditionalUrlParam('form[' . $id . '][' . $key . ']', $val);
                    }
                }
            }
        }

        return $pagination;
    }
}
