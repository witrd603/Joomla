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

class FrontendModelItemComments extends LoveFactoryFrontendModelList
{
    protected $itemType = null;
    protected $itemId = null;

    public function __construct($config = array())
    {
        parent::__construct($config);

        JFactory::getApplication()->input->set('limit', 10);
    }

    public function setItemType($type)
    {
        $this->itemType = strtolower($type);
    }

    public function getItemType()
    {
        if (is_null($this->itemType)) {
            $this->itemType = JFactory::getApplication()->input->getCmd('item_type', '');
        }

        return $this->itemType;
    }

    public function setItemId($id)
    {
        $this->itemId = $id;
    }

    public function getItemId()
    {
        if (is_null($this->itemId)) {
            $this->itemId = JFactory::getApplication()->input->getInt('item_id', 0);
        }

        return $this->itemId;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $user = JFactory::getUser();
        $array = array();

        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $adminDelete = $settings->admin_comments_delete && $user->authorise('core.login.admin');
        $userDelete = $settings->user_comments_delete;

        $type = $this->getItemType();
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('comment_' . $type . '_access');
        $restricted = false;

        try {
            $restriction->isAllowed($user->id);
        } catch (Exception $e) {
            $restricted = $e->getMessage();
        }

        foreach ($items as $item) {
            $table = $this->getTable('Profile');
            $table->bind($item);

            $item->thumbnail = $table->getMainPhotoSource(true);
            $item->isMyComment = $item->user_id == $user->id || $adminDelete;
            $item->isMyItem = $userDelete && $item->item_user_id == $user->id;

            if ($item->isMyItem && !$item->read) {
                $array[] = $item->id;
            }

            if (false !== $restricted) {
                $item->message = '<a href="' . FactoryRoute::view('memberships') . '">' . $restricted . '</a>';
            }
        }

        $this->markAsRead($array);

        return $items;
    }

    public function getPagination()
    {
        $pagination = parent::getPagination();

        $type = $this->getItemType();
        $view = 'profile' == $type ? 'comments' : $type;

        $pagination->setAdditionalUrlParam('option', 'com_lovefactory');
        $pagination->setAdditionalUrlParam('view', $view);
        $pagination->setAdditionalUrlParam('id', $this->getItemId());
        $pagination->setAdditionalUrlParam('format', '');
        $pagination->setAdditionalUrlParam('layout', '');
        $pagination->setAdditionalUrlParam('item_type', '');
        $pagination->setAdditionalUrlParam('item_id', '');

        return $pagination;
    }

    public function getApproval()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        $types = array(
            'photo' => $settings->approval_comments_photo,
            'video' => $settings->approval_comments_video,
            'profile' => $settings->approval_comments,
        );

        return $types[$this->getItemType()];
    }

    /**
     * Returns the unread count of comments.
     * @return mixed
     */
    public function getUnreadCount()
    {
        JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'tables');
        $dbo = $this->getDbo();
        $table = $this->getTable('ItemComment');

        $query = $dbo->getQuery(true)
            ->select('COUNT(1)')
            ->from($dbo->quoteName($table->getTableName()) . ' c')
            ->where('c.item_type = ' . $dbo->quote($this->getItemType()))
            ->where('c.item_id = ' . $dbo->quote($this->getItemId()))
            ->where('c.read = ' . $dbo->quote(0));

        // Filter by approved comments.
        if ($this->getApproval()) {
            $query->where('c.approved = ' . $dbo->quote(1));
        }

        $result = $dbo->setQuery($query)
            ->loadResult();

        return $result;
    }

    public function getUserId()
    {
        return JFactory::getUser()->id;
    }

    protected function getListQuery()
    {
        $query = parent::getListQuery();
        $table = $this->getTable('ItemComment');

        $query->select('c.*')
            ->from($query->quoteName($table->getTableName()) . ' c')
            ->where('c.item_type = ' . $query->quote($this->getItemType()))
            ->where('c.item_id = ' . $query->quote($this->getItemId()))
            ->order('c.created_at DESC');

        // Select user profile.
        $query->select('p.*')
            ->leftJoin('#__lovefactory_profiles p ON p.user_id = c.user_id');

        // Select the username.
        $query->select('u.username')
            ->leftJoin('#__users u ON u.id = c.user_id');

        // Filter approved comments.
        $this->addQueryApprovalCondition($query);

        return $query;
    }

    protected function markAsRead($items)
    {
        JArrayHelper::toInteger($items);

        if (!$items) {
            return true;
        }

        $dbo = $this->getDbo();
        $table = $this->getTable('ItemComment');
        $query = $dbo->getQuery(true)
            ->update($dbo->quoteName($table->getTableName()))
            ->set($dbo->quoteName('read') . ' = ' . $dbo->quote(1))
            ->where('id IN (' . implode(',', $items) . ')');

        return $dbo->setQuery($query)
            ->execute();
    }

    protected function addQueryApprovalCondition($query, $showOwn = true)
    {
        if (!$this->getApproval()) {
            return false;
        }

        $condition = 'c.approved = ' . $query->quote(1);

        if ($showOwn) {
            $condition = '(' . $condition . ' OR c.user_id = ' . JFactory::getUser()->id . ')';
        }

        $query->where($condition);
    }
}
