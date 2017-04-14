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

class TableGroup extends LoveFactoryTable
{
    function __construct(&$db)
    {
        parent::__construct('#__lovefactory_groups', 'id', $db);
    }

    function getLink()
    {
        $Itemid = JFactory::getApplication()->input->getInt('Itemid');

        return JRoute::_('index.php?option=com_lovefactory&view=group&group_id=' . $this->id . '&Itemid=' . $Itemid);
    }

    function getOwnerLink()
    {
        $Itemid = JFactory::getApplication()->input->getInt('Itemid');

        return JRoute::_('index.php?option=com_lovefactory&view=profile&id=' . $this->user_id . '&Itemid=' . $Itemid);
    }

    function getThumbnail($size = 'large')
    {
        jimport('joomla.filesystem.file');

        $app = LoveFactoryApplication::getInstance();
        $filename = 'group' . ('large' == $size ? '' : '_thumb') . '_' . $this->thumbnail;
        $filepath = $app->getUserFolder($this->user_id) . DS . $filename;

        if (JFile::exists($filepath)) {
            return $app->getUserFolder($this->user_id, true) . $filename;
        }

        return $app->getAssetsFolder('images', true) . 'love.png';
    }

    function isMyGroup()
    {
        return $this->isOwner();
    }

    function store($updateNulls = false)
    {
        if (is_null($this->id) || empty($this->id)) {
            $user = JFactory::getUser();
            $date = JFactory::getDate();

            $this->created_at = $date->toSql();
            $this->user_id = $user->id;
        }

        return parent::store($updateNulls);
    }

    public function delete($pk = null)
    {
        if (!parent::delete($pk)) {
            return false;
        }

        $dbo = $this->getDbo();

        $this->deleteDependencies($dbo, $pk);

        return true;
    }

    protected function deleteDependencies($dbo, $pk)
    {
        // Initialise variables.
        $k = $this->_tbl_key;
        $pk = (is_null($pk)) ? $this->$k : $pk;

        $queries = array();
        $queries[] = $this->deleteBans($pk, $dbo);
        $queries[] = $this->deleteMembers($pk, $dbo);
        $queries[] = $this->deletePosts($pk, $dbo);
        $queries[] = $this->deleteThreads($pk, $dbo);

        foreach ($queries as $query) {
            $dbo->setQuery($query)
                ->query();
        }

        return true;
    }

    protected function deleteBans($pk, $dbo)
    {
        $table = JTable::getInstance('GroupBan', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('group_id = ' . $dbo->quote($pk));

        return $query;
    }

    protected function deleteMembers($pk, $dbo)
    {
        $table = JTable::getInstance('GroupMember', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('group_id = ' . $dbo->quote($pk));

        return $query;
    }

    protected function deletePosts($pk, $dbo)
    {
        $table = JTable::getInstance('GroupPost', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('group_id = ' . $dbo->quote($pk));

        return $query;
    }

    protected function deleteThreads($pk, $dbo)
    {
        $table = JTable::getInstance('GroupThread', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('group_id = ' . $dbo->quote($pk));

        return $query;
    }

    public function approve()
    {
        $this->approved = 1;

        if (!$this->store()) {
            return false;
        }

        JEventDispatcher::getInstance()->trigger('onLoveFactoryGroupApproved', array(
            'com_lovefactory.group_approved',
            $this,
        ));

        return true;
    }

    public function reject()
    {
        return $this->delete();
    }

    public function report()
    {
        $this->reported = 1;

        return $this->store();
    }

    public function userIsMember($userId = null)
    {
        if (is_null($userId)) {
            $userId = JFactory::getUser()->id;
        }

        $table = JTable::getInstance('GroupMember', 'Table');

        if (!$table->load(array('group_id' => $this->id, 'user_id' => $userId))) {
            return false;
        }

        return $table->id;
    }

    public function check()
    {
        if (!parent::check()) {
            return false;
        }

        return true;
    }

    public function isApproved()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if (!$settings->approval_groups || $this->approved) {
            return true;
        }

        $this->setError(FactoryText::_('group_table_not_approved'));

        return false;
    }

    public function isOwner($userId = null)
    {
        if (is_null($userId)) {
            $userId = JFactory::getUser()->id;
        }

        return $userId == $this->user_id;
    }

    public function registerActivity($created_at = null)
    {
        /* @var $activity TableActivity */
        // Initialise variables.
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $activity = JTable::getInstance('Activity', 'Table');

        // Check if groups approvals are enabled and group is approved.
        if ($settings->approval_groups && !$this->approved) {
            return true;
        }

        return $activity->register(
            'group_create',
            $this->user_id,
            $this->user_id,
            $this->id,
            array(
                'title' => $this->title
            ),
            $created_at
        );
    }
}
