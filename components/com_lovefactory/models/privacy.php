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

class FrontendModelPrivacy extends FactoryModel
{
    protected $statuses = array('public' => 0, 'friends' => 1, 'private' => 2);
    protected $types = array('photo', 'video');
    protected $updated = array();
    protected $profile;
    protected $tables = array();

    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->tables['profile'] = isset($config['profile']) ? $config['profile'] : $this->getTable('Profile');
        $this->tables['photo'] = isset($config['photo']) ? $config['photo'] : $this->getTable('Photo');
        $this->tables['video'] = isset($config['video']) ? $config['video'] : $this->getTable('Video', 'TableLoveFactory');
    }

    public function setPrivacy($userId, $privacy, $type, $batch = array())
    {
        // Check if any item was selected.
        if (!is_array($batch) || !$batch) {
            $this->setError(FactoryText::_('privacy_set_error_select_items'));
            return false;
        }

        // Check if privacy is valid.
        if (!array_key_exists($privacy, $this->statuses)) {
            $this->setError(FactoryText::_('privacy_set_error_invalid_privacy'));
            return false;
        }

        // Check if item type is valid.
        if (!in_array($type, $this->types)) {
            $this->setError(FactoryText::_('privacy_set_error_invalid_type'));
            return false;
        }

        $doCheck = $this->performCheck($userId, $privacy, $type);
        $result = $this->processBatch($userId, $type, $batch, $privacy, $doCheck);

        $this->setState('items.updated', $this->updated);

        return $result;
    }

    protected function performCheck($userId, $privacy, $type)
    {
        // We always allow user to set item privacy to private.
        if ('private' == $privacy) {
            return false;
        }

        $restriction = $this->getRestriction($userId, $type);

        if (-1 === $restriction['allowed']) {
            return false;
        }

        return $restriction;
    }

    protected function getRestriction($userId, $type, $statuses = array(0, 1))
    {
        $table = $this->tables[$type];
        $count = $table->getCountForUser($userId, $statuses);

        $restrictions = array(
            'photo' => 'photos',
            'video' => 'videos',
        );

        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction($restrictions[$type]);
        $allowed = $restriction->getCurrentMembershipRestriction($userId);

        return array('count' => $count, 'allowed' => $allowed);
    }

    protected function processBatch($userId, $type, $batch, $privacy, $doCheck)
    {
        $this->updated = array();

        foreach ($batch as $itemId) {
            $item = $this->tables[$type];

            // Load the item.
            if (!$itemId || !$item->load($itemId)) {
                $this->setError(FactoryText::_('privacy_set_error_item_not_found_' . $type));
                return false;
            }

            // Check if user is owner of the item.
            if ($item->user_id != $userId) {
                $this->setError(FactoryText::_('privacy_set_error_not_allowed'));
                return false;
            }

            $oldStatus = $item->status;

            if ($doCheck && 2 == $item->status && $doCheck['count'] >= $doCheck['allowed']) {
                $this->setError(FactoryText::sprintf('privacy_set_error_membership_resrtiction_' . $type, $doCheck['allowed']));
                return false;
            }

            // Update privacy.
            $item->status = $this->statuses[$privacy];

            // Save the item.
            if (!$item->store()) {
                return false;
            }

            if ($doCheck && 2 == $oldStatus) {
                $doCheck['count']++;
            }

            array_push($this->updated, $itemId);
        }

        return true;
    }
}
