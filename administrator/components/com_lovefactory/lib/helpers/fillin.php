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

class FillInNotificationHelper
{
    private $dbo;

    public function __construct(JDatabaseDriver $dbo)
    {
        $this->dbo = $dbo;

        JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_lovefactory/tables');
    }

    public function setReminder($userId)
    {
        $table = $this->getTable();
        $dbo = $this->dbo;

        if ($table->load($userId)) {
            return true;
        }

        $profile = JTable::getInstance('Profile', 'Table');
        $profile->load($userId);

        if (!$profile->user_id || $profile->filled) {
            return true;
        }

        $query = $dbo->getQuery(true)
            ->insert($dbo->qn($table->getTableName()))
            ->set('user_id = ' . $dbo->q($userId))
            ->set('registered_at = ' . $dbo->q($profile->date));

        $dbo->setQuery($query)
            ->execute();

        return true;
    }

    public function setReminders(array $users = array())
    {
        $dbo = $this->dbo;

        $dbo->transactionStart();

        foreach ($users as $userId) {
            $this->setReminder($userId);
        }

        $dbo->transactionCommit();

        return true;
    }

    public function clearReminders()
    {
        $table = $this->getTable();
        $dbo = $this->dbo;

        $query = $dbo->getQuery(true)
            ->delete($dbo->qn($table->getTableName()));

        $dbo->setQuery($query)
            ->execute();

        return true;
    }

    public function sendNotifications($interval = '-30 days')
    {
        // Initialise variables.
        $limit = JFactory::getDate($interval)->toSql();
        $dbo = $this->dbo;

        // Get pending notifications.
        $query = $dbo->getQuery(true)
            ->select('n.user_id, u.username')
            ->from($dbo->qn('#__lovefactory_fillin_notifications', 'n'))
            ->leftJoin($dbo->qn('#__users', 'u') . ' ON u.id = n.user_id')
            ->where('n.registered_at <= ' . $dbo->q($limit));

        $results = $dbo->setQuery($query)
            ->loadObjectList();

        // Check if any pending notification was found.
        if (!$results) {
            return true;
        }

        $dispatcher = JEventDispatcher::getInstance();
        $array = array();

        // Send notifications.
        foreach ($results as $result) {
            $sent = $dispatcher->trigger('onLoveFactoryProfileFillinReminder', array(
                'com_lovefactory.fillin.reminder',
                $result->user_id,
                $result->username,
            ));

            // Mark notification as sent.
            if ($sent) {
                $array[] = $result->user_id;
            }
        }

        // Delete notifications marked as sent.
        if ($array) {
            $query = $dbo->getQuery(true)
                ->delete($dbo->qn('#__lovefactory_fillin_notifications'))
                ->where('user_id IN (' . implode(',', $dbo->q($array)) . ')');
            $dbo->setQuery($query)
                ->execute();
        }

        return true;
    }

    public function removeObsoleteReminders()
    {
        $dbo = $this->dbo;

        // Remove notifications for users that already have a Love Factory profile.
        $query = ' DELETE n'
            . ' FROM ' . $dbo->qn('#__lovefactory_fillin_notifications') . ' n'
            . ' LEFT JOIN ' . $dbo->qn('#__lovefactory_profiles') . ' p ON p.user_id = n.user_id'
            . ' WHERE p.filled = 1';

        $dbo->setQuery($query)
            ->execute();

        return true;
    }

    private function getTable()
    {
        return JTable::getInstance('FillInNotification', 'LoveFactoryTable');
    }
}
