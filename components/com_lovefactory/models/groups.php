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

class FrontendModelGroups extends LoveFactoryFrontendModelList
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
            '' => array('text' => FactoryText::_('groups_filter_sort_title'), 'column' => 'g.title'),
            'members' => array('text' => FactoryText::_('groups_filter_sort_members'), 'column' => 'members'),
            'last_activity' => array('text' => FactoryText::_('groups_filter_sort_last_activity'), 'column' => 'last_activity'),
        );
    }

    public function getFilterType()
    {
        $value = $this->getFilterValue('type');

        $select = JHtml::_(
            'select.genericlist',
            array(
                '' => FactoryText::_('groups_filter_groups_all'),
                'owner' => FactoryText::_('groups_filter_groups_owner'),
                'member' => FactoryText::_('groups_filter_groups_member'),
            ),
            'filter[type]',
            '',
            '',
            '',
            $value
        );

        return $select;
    }

    public function getFilterPrivate()
    {
        $value = $this->getFilterValue('private');

        $select = JHtml::_(
            'select.genericlist',
            array(
                '' => FactoryText::_('groups_filter_private_all'),
                'public' => FactoryText::_('groups_filter_private_public'),
                'private' => FactoryText::_('groups_filter_private_private'),
            ),
            'filter[private]',
            '',
            '',
            '',
            $value
        );

        return $select;
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
                '' => FactoryText::_('list_filter_order_asc'),
                'desc' => FactoryText::_('list_filter_order_desc'),
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

        return $settings->approval_groups;
    }

    protected function getListQuery()
    {
        $query = parent::getListQuery();
        $user = JFactory::getUser();

        $query->select('g.*')
            ->from('#__lovefactory_groups g')
            ->group('g.id');

        // Select the number of members.
        $query->select('COUNT(DISTINCT m.id) AS members')
            ->leftJoin('#__lovefactory_group_members m ON m.group_id = g.id');

        // Select last activity.
        $query->select('GREATEST(COALESCE(MAX(p.created_at), 0), COALESCE(MAX(t.created_at), 0)) AS last_activity')
            ->leftJoin('#__lovefactory_group_posts p ON p.group_id = g.id')
            ->leftJoin('#__lovefactory_group_threads t ON t.group_id = g.id');

        // Filter results.
        $this->addFilterTypeCondition($query);
        $this->addFilterPrivateCondition($query);
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

    protected function addFilterTypeCondition($query)
    {
        $value = $this->getFilterValue('type');
        $user = JFactory::getUser();

        if ('owner' == $value) {
            $query->where('g.user_id = ' . $query->quote($user->id));
        } elseif ('member' == $value) {
            $query->leftJoin('#__lovefactory_group_members m2 ON m2.group_id = g.id AND m2.user_id = ' . $query->quote($user->id))
                ->where('m2.id IS NOT NULL');
        }
    }

    protected function addFilterPrivateCondition($query)
    {
        $value = $this->getFilterValue('private');

        if ('' != $value) {
            $query->where('g.private = ' . $query->quote('private' == $value ? 1 : 0));
        }
    }

    protected function addFilterSearchCondition($query)
    {
        $value = $this->getFilterValue('search');
        if ('' != $value) {
            $query->where('g.title LIKE ' . $query->quote('%' . $value . '%'));
        }
    }

    protected function addFilterApprovedCondition($query, $showOwn = true)
    {
        if (!$this->getApproval()) {
            return true;
        }

        $condition = 'g.approved = ' . $query->quote(1);

        if ($showOwn) {
            $condition = '(' . $condition . ' OR g.user_id = ' . JFactory::getUser()->id . ')';
        }

        $query->where($condition);
    }

    protected function addOrder($query)
    {
        $sort = $this->getFilterValue('sort');
        $order = $this->getFilterValue('order');

        $sort = $this->sort[$sort]['column'];
        $order = in_array($order, array('asc', 'desc')) ? $order : 'asc';

        $query->order($sort . ' ' . $order);
    }
}
