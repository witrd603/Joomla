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

jimport('joomla.application.component.model');

class FrontendModelItemComment extends FactoryModel
{
    public function save($data)
    {
        // Check if the item exists.
        $item = $this->loadItem($data['item_type'], $data['item_id']);

        if (!$item) {
            $this->setError(FactoryText::_('itemcomment_task_save_asset_not_found'));
            return false;
        }

        // Check for same gender interaction.
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('same_gender_interaction');
        try {
            $restriction->isAllowed($data['user_id'], $item->user_id);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            $this->setState('membership_restriction_error', true);

            return false;
        }

        // Check if user is blacklisted.
        $modelBlacklist = JModelLegacy::getInstance('Blacklist', 'FrontendModel');
        $blocked = $modelBlacklist->isBlacklisted($data['user_id'], $item->user_id);

        if ($blocked) {
            $this->setError(FactoryText::_('profile_interaction_not_allowed_user_blocked_you'));
            return false;
        }

        $data['item_user_id'] = $item->user_id;

        // Save the comment.
        $table = $this->getTable('ItemComment');

        $data['message'] = strip_tags($data['message']);

        if (!$table->save($data)) {
            $this->setError($table->getError());
            return false;
        }

        // Register activity.
        JEventDispatcher::getInstance()->trigger('onLoveFactoryCommentReceived', array(
            'com_lovefactory.comment_received',
            $table
        ));

        return true;
    }

    public function delete($id)
    {
        // Initialise variables.
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $user = JFactory::getUser();
        $table = $this->getTable('ItemComment');
        $adminDelete = $settings->admin_comments_delete && $user->authorise('core.login.admin');
        $userDelete = $settings->user_comments_delete;

        // Load the comment.
        $result = $table->load($id);

        // Check if comment was found.
        if (!$result) {
            $this->setError(FactoryText::_('itemcomment_task_delete_error_comment_not_found'));
            return false;
        }

        // Check if the user is allowed to delete the comment.
        if ($table->user_id != $user->id && (!$userDelete || $table->item_user_id != $user->id) && !$adminDelete) {
            $this->setError(FactoryText::_('itemcomment_task_delete_error_not_allowed'));
            return false;
        }

        // Delete comment.
        if (!$table->delete()) {
            $this->setError($table->getError());
            return false;
        }

        return true;
    }

    public function report($id)
    {
        // Initialise variables.
        $table = $this->getTable('ItemComment');

        // Load the comment.
        $result = $table->load($id);

        // Check if comment was found.
        if (!$result) {
            $this->setError(FactoryText::_('itemcomment_task_report_error_comment_not_found'));
            return false;
        }

        // Report comment.
        if (!$table->report()) {
            $this->setError($table->getError());
            return false;
        }

        return true;
    }

    protected function loadItem($type, $id)
    {
        if (!$id) {
            return false;
        }

        $types = array(
            'photo' => array('table' => 'Photo', 'item_id' => 'id'),
            'video' => array('table' => 'LoveFactoryVideo', 'item_id' => 'id'),
            'profile' => array('table' => 'Profile', 'item_id' => 'user_id'),
        );
        $table = $this->getTable($types[$type]['table']);

        if (!$table->load($id)) {
            return false;
        }

        if ($table->{$types[$type]['item_id']} != $id) {
            return false;
        }

        return $table;
    }
}
