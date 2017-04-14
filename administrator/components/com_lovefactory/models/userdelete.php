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

class BackendModelUserDelete extends JModelLegacy
{
    public function deleteUserDependencies(TableProfile $user)
    {
        // Initialise variables.
        $pk = $user->user_id;
        $dbo = $this->getDbo();

        $this->deleteUserFolder($pk);

        $queries = array();
        $queries[] = $this->deleteActivity($pk, $dbo);
        $queries[] = $this->deleteApprovals($pk, $dbo);
        $queries[] = $this->deleteBlacklist($pk, $dbo);
        $queries[] = $this->deleteFriends($pk, $dbo);
        $queries[] = $this->deleteGroupBans($pk, $dbo);
        $queries[] = $this->deleteGroupMembers($pk, $dbo);
        $queries[] = $this->deleteGroups($pk, $dbo);
        $queries[] = $this->deleteInteractions($pk, $dbo);
        $queries[] = $this->deleteInvoices($pk, $dbo);
        $queries[] = $this->deleteIps($pk, $dbo);
        $queries[] = $this->deleteItemComments($pk, $dbo);
        $queries[] = $this->deleteMembershipsSold($pk, $dbo);
        $queries[] = $this->deleteMessages($pk, $dbo);
        $queries[] = $this->deleteOrders($pk, $dbo);
        $queries[] = $this->deleteDependencyPhotos($pk, $dbo);
        $queries[] = $this->deleteProfileUpdates($pk, $dbo);
        $queries[] = $this->deleteProfileVisitors($pk, $dbo);
        $queries[] = $this->deleteReceivedRatings($pk, $dbo);
        $queries[] = $this->deleteStatisticsPerDay($pk, $dbo);
        $queries[] = $this->deleteDependencyVideos($pk, $dbo);

        // TODO (alex): Deleted Chat Factory profile.

        $dbo->transactionStart();

        foreach ($queries as $query) {
            $dbo->setQuery($query)
                ->execute();
        }

        $dbo->transactionCommit();

        $this->deleteAwardedRatings($pk, $dbo);

        return true;
    }

    private function deleteUserFolder($pk)
    {
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');

        JLoader::register('LoveFactoryApplication', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'lib' . DS . 'application.php');
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if (1 == $settings->photos_storage_mode) {
            $app = LoveFactoryApplication::getInstance();
            $folder = $app->getUserFolder($pk);

            if (JFolder::exists($folder)) {
                return JFolder::delete($folder);
            }
        }

        return true;
    }

    private function deleteActivity($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('Activity', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('receiver_id = ' . $dbo->quote($userId));

        return $query;
    }

    private function deleteApprovals($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('Approval', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('user_id = ' . $dbo->quote($userId));

        return $query;
    }

    private function deleteBlacklist($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('Blacklist', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('sender_id = ' . $dbo->quote($userId), 'OR')
            ->where('receiver_id = ' . $dbo->quote($userId));

        return $query;
    }

    private function deleteFriends($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('Friend', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('sender_id = ' . $dbo->quote($userId), 'OR')
            ->where('receiver_id = ' . $dbo->quote($userId));

        return $query;
    }

    private function deleteGroupBans($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('GroupBan', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('user_id = ' . $dbo->quote($userId));

        return $query;
    }

    private function deleteGroupMembers($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('GroupMember', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('user_id = ' . $dbo->quote($userId));

        return $query;
    }

    private function deleteGroups($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('Group', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('user_id = ' . $dbo->quote($userId));

        return $query;
    }

    private function deleteInteractions($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('Interaction', 'Table');

        $query = $dbo->getQuery(true)
            ->update($dbo->quoteName($table->getTableName()))
            ->set('deleted_by_sender = IF (' . $dbo->quote($userId) . ' = sender_id, 1, deleted_by_sender)')
            ->set('deleted_by_receiver = IF (' . $dbo->quote($userId) . ' = receiver_id, 1, deleted_by_receiver)');

        return $query;
    }

    private function deleteInvoices($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('Invoice', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('user_id = ' . $dbo->quote($userId));

        return $query;
    }

    private function deleteIps($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('Ip', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('user_id = ' . $dbo->quote($userId));

        return $query;
    }

    private function deleteItemComments($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('ItemComment', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('item_user_id = ' . $dbo->quote($userId));

        return $query;
    }

    private function deleteMembershipsSold($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('MembershipSold', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('user_id = ' . $dbo->quote($userId));

        return $query;
    }

    private function deleteMessages($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('LoveFactoryMessage', 'Table');

        $query = $dbo->getQuery(true)
            ->update($dbo->quoteName($table->getTableName()))
            ->set('deleted_by_sender = IF (' . $dbo->quote($userId) . ' = sender_id, 1, deleted_by_sender)')
            ->set('deleted_by_receiver = IF (' . $dbo->quote($userId) . ' = receiver_id, 1, deleted_by_receiver)');

        return $query;
    }

    private function deleteOrders($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('Order', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('user_id = ' . $dbo->quote($userId));

        return $query;
    }

    private function deleteDependencyPhotos($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('Photo', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('user_id = ' . $dbo->quote($userId));

        return $query;
    }

    private function deleteProfileUpdates($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('ProfileUpdate', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('user_id = ' . $dbo->quote($userId));

        return $query;
    }

    private function deleteProfileVisitors($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('ProfileVisitor', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('user_id = ' . $dbo->quote($userId));

        return $query;
    }

    private function deleteReceivedRatings($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('Rating', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('receiver_id = ' . $dbo->quote($userId));

        return $query;
    }

    private function deleteAwardedRatings($userId, JDatabaseDriver $dbo)
    {
        // Initialise variables.
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        // Check if awarded ratings should be removed.
        if (!$settings->remove_ratings_on_profile_remove) {
            return true;
        }

        // Get awarded votes.
        $query = $dbo->getQuery(true)
            ->select('r.receiver_id, r.rating')
            ->from('#__lovefactory_ratings r')
            ->where('r.sender_id = ' . $dbo->q($userId));
        $users = $dbo->setQuery($query)
            ->loadObjectList();

        // Update rating for profiles with user awarded votes.
        foreach ($users as $user) {
            $query = $dbo->getQuery(true)
                ->update('#__lovefactory_profiles')
                ->set(' rating = ((rating * votes - ' . intval($user->rating) . ') / (votes - 1)), votes = votes - 1')
                ->where('user_id = ' . $dbo->q($user->receiver_id));
            $dbo->setQuery($query)
                ->execute();
        }

        // Remove awarded votes.
        $query = $dbo->getQuery(true)
            ->delete()
            ->from('#__lovefactory_ratings')
            ->where('sender_id = ' . $dbo->q($userId));
        $dbo->setQuery($query)
            ->execute();

        return $query;
    }

    private function deleteStatisticsPerDay($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('StatisticsPerDay', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('user_id = ' . $dbo->quote($userId));

        return $query;
    }

    private function deleteDependencyVideos($userId, JDatabaseDriver $dbo)
    {
        $table = JTable::getInstance('LoveFactoryVideo', 'Table');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($table->getTableName()))
            ->where('user_id = ' . $dbo->quote($userId));

        return $query;
    }
}
