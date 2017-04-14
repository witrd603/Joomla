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

class FrontendModelGroupPosts extends FactoryModelList
{
    protected $threadId;

    public function __construct($config = array())
    {
        parent::__construct($config);

        $limit = LoveFactoryApplication::getInstance()->getSettings('group_posts_list_limit', 10);
        JFactory::getApplication()->input->set('limit', $limit);

        $this->sort = array(
            '' => array('text' => FactoryText::_('groupposts_filter_sort_date'), 'column' => 'created_at'),
        );
    }

    public function getItems($threadId = null)
    {
        $this->threadId = $threadId;
        $items = parent::getItems();
        $user = JFactory::getUser();

        foreach ($items as $item) {
            $table = $this->getTable('Profile');
            $table->bind($item);

            $item->thumbnail = $table->getMainPhotoSource(true);
            $item->isMyComment = $item->user_id == $user->id;
        }

        return $items;
    }

    public function getApproval()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        return $settings->approval_groups_posts;
    }

    protected function addOrder($query)
    {
        $sort = $this->getFilterValue('sort');
        $order = $this->getFilterValue('order');

        $sort = $this->sort[$sort]['column'];
        $order = in_array($order, array('asc', 'desc')) ? $order : 'desc';

        $query->order($sort . ' ' . $order);
    }

    protected function getListQuery()
    {
        $query = parent::getListQuery();

        $query->select('p.*')
            ->from('#__lovefactory_group_posts p')
            ->where('p.thread_id = ' . $query->quote($this->threadId));

        // Select username.
        $query->select('u.username')
            ->leftJoin('#__users u ON u.id = p.user_id');

        // Select profile.
        $query->select('prf.*')
            ->leftJoin('#__lovefactory_profiles prf ON prf.user_id = p.user_id');

        // Select if user is banned.
        $query->select('b.id AS is_banned')
            ->leftJoin('#__lovefactory_group_bans b ON b.user_id = p.user_id AND b.group_id = p.group_id');

        // Filter by approved posts.
        $this->addFilterApprovedCondition($query);

        return $query;
    }

    protected function addFilterApprovedCondition($query, $showOwn = true)
    {
        if (!$this->getApproval()) {
            return true;
        }

        $condition = 'p.approved = ' . $query->quote(1);

        if ($showOwn) {
            $condition = '(' . $condition . ' OR p.user_id = ' . JFactory::getUser()->id . ')';
        }

        $query->where($condition);
    }
}
