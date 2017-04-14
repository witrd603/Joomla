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

class FrontendModelGroupMembers extends LoveFactoryFrontendModelList
{
    protected $filters;
    protected $sort = array();
    protected $group = null;

    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->filters = JFactory::getApplication()->input->get('filter', array(), 'array');
        $this->sort = array(
            '' => array('text' => FactoryText::_('groupmembers_filter_sort_member'), 'column' => 'p.display_name'),
            'since' => array('text' => FactoryText::_('groupmembers_filter_sort_since'), 'column' => 'm.created_at'),
        );
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

    public function getGroup()
    {
        if (null === $this->group) {
            $id = JFactory::getApplication()->input->getInt('id', 0);
            $group = $this->getTable('Group');
            $user = JFactory::getUser();

            if (!$group->load($id)) {
                throw new Exception('Group not found!', 404);
            }

            if ($group->private) {
                $table = $this->getTable('GroupMember', 'Table');

                $data = array(
                    'group_id' => $group->id,
                    'user_id' => $user->id,
                );

                if (!$table->load($data)) {
                    throw new Exception('Group is private!', 403);
                }
            }

            $this->group = $group;
        }

        return $this->group;
    }

    public function removeUsers($batch, $id)
    {
        $user = JFactory::getUser();
        JArrayHelper::toInteger($batch);

        // Check if batch is empty.
        if (!$batch) {
            $this->setError(FactoryText::_('batch_no_item_selected'));
            return false;
        }

        // Check if user is group owner.
        $table = $this->getTable('Group');
        $table->load($id);

        if ($table->user_id != $user->id) {
            $this->setError(FactoryText::_('groupmembers_task_remove_error_not_allowed'));
            return false;
        }

        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->delete()
            ->from('#__lovefactory_group_members')
            ->where('user_id IN (' . implode(',', $batch) . ')')
            ->where('group_id = ' . $dbo->quote($id))
            ->where('user_id <> ' . $dbo->q($table->user_id));

        return $dbo->setQuery($query)
            ->execute();
    }

    protected function getListQuery()
    {
        $query = parent::getListQuery();
        $id = JFactory::getApplication()->input->getInt('id', 0);

        $query->select('m.user_id, m.created_at')
            ->from('#__lovefactory_group_members m')
            ->where('m.group_id = ' . $query->quote($id));

        $query->select('p.display_name')
            ->leftJoin('#__lovefactory_profiles AS p ON p.user_id = m.user_id');

        //
        $query->select('b.id AS banned')
            ->leftJoin('#__lovefactory_group_bans b ON b.group_id = ' . $query->quote($id) . ' AND b.user_id = m.user_id');

        // Filter results.
        $this->addFilterSearchCondition($query);

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
            $query->where('p.display_name LIKE ' . $query->quote('%' . $value . '%'));
        }
    }

    protected function addOrder($query)
    {
        $sort = $this->getFilterValue('sort');
        $order = $this->getFilterValue('order');

        $sort = $this->sort[$sort]['column'];
        $order = in_array($order, array('asc', 'desc')) ? $order : 'asc';

        $group = $this->getGroup();

        if ($group) {
            $query->order(' CASE m.user_id WHEN ' . $query->quote($group->user_id) . ' THEN 0 END DESC, ' . $sort . ' ' . $order);
        }
    }
}
