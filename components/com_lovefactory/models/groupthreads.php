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

class FrontendModelGroupThreads extends LoveFactoryFrontendModelList
{
    protected $filters;
    protected $sort = array();

    public function __construct($config = array())
    {
        parent::__construct($config);

        $limit = LoveFactoryApplication::getInstance()->getSettings('groups_list_limit', 10);
        JFactory::getApplication()->input->set('limit', $limit);

        $this->filters = JFactory::getApplication()->input->get('filter', array(), 'array');
        $this->sort = array(
            '' => array('text' => FactoryText::_('groupthreads_filter_sort_last_activity'), 'column' => 'last_activity'),
            'posts' => array('text' => FactoryText::_('groupthreads_filter_sort_posts'), 'column' => 'posts'),
        );
    }

    public function getGroup()
    {
        $model = JModelLegacy::getInstance('Group', 'FrontendModel');
        $group = $model->getItem();

        if (!$group) {
            return false;
        }

        if ($group->private) {
            $table = $this->getTable('GroupMember', 'Table');
            if (!$table->load(array('group_id' => $group->id, 'user_id' => JFactory::getUser()->id))) {
                return false;
            }
        }

        return $group;
    }

    public function getFilterSort()
    {
        $value = $this->getFilterValue('sort');
        $array = array();

        foreach ($this->sort as $val => $sort) {
            $array[] = array('value' => $val, 'text' => $sort['text']);
        }

        $select = JHtml::_(
            'select.genericlist',
            $array,
            'filter[sort]',
            '',
            'value',
            'text',
            $value
        );

        return $select;
    }

    public function getFilterOrder()
    {
        $value = $this->getFilterValue('order');

        $select = JHtml::_(
            'select.genericlist',
            array(
                'asc' => FactoryText::_('list_filter_order_asc'),
                '' => FactoryText::_('list_filter_order_desc'),
            ),
            'filter[order]',
            '',
            '',
            '',
            $value
        );

        return $select;
    }

    public function getFilterSearch()
    {
        $value = $this->getFilterValue('search');

        return '<input type="text" size="20" name="filter[search]" id="filtersearch" value="' . htmlentities($value) . '" />';
    }

    public function getApproval()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        return $settings->approval_group_threads;
    }

    protected function getListQuery()
    {
        $query = parent::getListQuery();
        $user = JFactory::getUser();

        $query->select('t.*')
            ->from('#__lovefactory_group_threads t')
            ->where('t.group_id = ' . $query->quote($this->getGroup()->id));

        // Select the number of posts.
        $query->select('COUNT(p.id) AS posts, (CASE WHEN COUNT(p.id) = 0 THEN t.created_at ELSE MAX(p.created_at) END) AS last_activity')
            ->leftJoin('#__lovefactory_group_posts p ON p.thread_id = t.id')
            ->group('t.id');

        // Filter results.
        $this->addFilterSearchCondition($query);
        $this->addFilterApprovedCondition($query);

        // Order results.
        $this->addOrder($query);

        return $query;
    }

    protected function getFilterValue($filter)
    {
        if (!isset($this->filters[$filter])) {
            return null;
        }

        return $this->filters[$filter];
    }

    protected function addFilterSearchCondition($query)
    {
        $value = $this->getFilterValue('search');
        if ('' != $value) {
            $query->where('t.title LIKE ' . $query->quote('%' . $value . '%'));
        }
    }

    protected function addFilterApprovedCondition($query, $showOwn = true)
    {
        if (!$this->getApproval()) {
            return true;
        }

        $condition = 't.approved = ' . $query->quote(1);

        if ($showOwn) {
            $condition = '(' . $condition . ' OR t.user_id = ' . JFactory::getUser()->id . ')';
        }

        $query->where($condition);
    }

    protected function addOrder($query)
    {
        $sort = $this->getFilterValue('sort');
        $order = $this->getFilterValue('order');

        $sort = $this->sort[$sort]['column'];
        $order = in_array($order, array('asc', 'desc')) ? $order : 'desc';

        $query->order($sort . ' ' . $order);
    }
}
