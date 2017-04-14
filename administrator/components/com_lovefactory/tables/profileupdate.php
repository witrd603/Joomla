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

class TableProfileUpdate extends JTable
{
    public $id = null;
    public $user_id = null;
    public $profile = null;
    public $created_at = null;
    public $pending = null;
    public $approved = null;

    public function __construct(&$db)
    {
        parent::__construct('#__lovefactory_profile_updates', 'id', $db);
    }

    public function createFrom($userId, $profile)
    {
        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('u.id')
            ->from($this->getTableName() . ' u')
            ->where('u.user_id = ' . $dbo->quote($userId));
        $result = $dbo->setQuery($query)
            ->loadResult();

        if ($result) {
            $this->id = $result;
        }

        $params = new JRegistry($profile);

        $this->user_id = $userId;
        $this->profile = $params->toString();

        return $this->store();
    }

    public function store($updateNulls = false)
    {
        if (!$this->id) {
            $this->created_at = JFactory::getDate()->toSql();
        }

        return parent::store($updateNulls);
    }

    public function loadLatestProfile($user_id)
    {
        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('u.id')
            ->from($this->getTableName() . ' u')
            ->where('u.user_id = ' . $dbo->quote($user_id))
            ->order('u.created_at DESC');
        $result = $dbo->setQuery($query)
            ->loadResult();

        if (!$result) {
            return false;
        }

        $this->load($result);
        $this->profile = new JRegistry($this->profile);

        return $this->profile->toObject();
    }

    public function loadLatest($user_id)
    {
        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('u.*')
            ->from($this->getTableName() . ' u')
            ->where('u.user_id = ' . $dbo->quote($user_id))
            ->order('u.created_at DESC');
        $result = $dbo->setQuery($query)
            ->loadObject();

        if (!$result) {
            return false;
        }

        $this->bind($result);
    }

    public function submitForApproval()
    {
        $this->pending = 1;

        return $this->store();
    }

    public function reject()
    {
        $this->pending = 0;
        $this->approved = 0;

        return $this->store();
    }

    public function approve()
    {
        $profile = JTable::getInstance('Profile', 'Table');
        $profile->load($this->user_id);

        if (!$profile->user_id) {
            return $this->delete();
        }

        $params = new JRegistry($this->profile);

        //JLoader::register('LoveFactoryPage', JPATH_COMPONENT_SITE.DS.'lib'.DS.'vendor'.DS.'page.php');
        //$page = LoveFactoryPage::getInstance('profile_edit', 'edit');
        //$page->bind($params->toArray());
        //$page->bindOriginalProfile($profile);
        //$profile->bindFromRequest($page->convertDataToProfile());

        $profile->bindFromRequest($params->toArray());

        $dispatcher = JEventDispatcher::getInstance();
        $dispatcher->trigger('onLoveFactoryProfileBeforeSave', array(
            'com_lovefactory.profile.save.before',
            $profile
        ));

        if (!$profile->store()) {
            return false;
        }

        $this->delete();

        return true;
    }
}
