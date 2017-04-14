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

class FrontendModelCronJob extends JModelLegacy
{
    protected $settings;
    protected $app;
    /** @var Logger */
    protected $logger;
    protected $_errors = array();

    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->app = LoveFactoryApplication::getInstance();
        $this->settings = $this->app->getSettings();
        $this->logger = $config['logger'];
    }

    public function getDbo()
    {
        $dbo = parent::getDbo();

        $dbo->setDebug(1);

        return $dbo;
    }

    public function execute($password)
    {
        $this->logger->log('Cron Job started!', true);

        if ($password === $this->settings->cron_password) {
            $this->removeActivityEntries();
            $this->sendEndMembershipNotifications();
            $this->removeShoutBoxEntries();
            $this->removeProfileVisitors();
            $this->removeApprovalEntries();
            $this->sendFillinNotifications();
            $this->removeTempProfilePhotos();
        } else {
            $this->setError('Provided password is invalid!');
            $this->logger->log('Cron Job failed: wrong password!');
        }

        $this->markExecution();
    }

    /**
     * Removes old approval entires.
     *
     * @return bool
     */
    protected function removeApprovalEntries()
    {
        $dbo = $this->getDbo();
        $limit = JFactory::getDate('-1 month')->toSql();

        $this->logger->log('Removing approval entries!');

        $query = $dbo->getQuery(true)
            ->delete()
            ->from('#__lovefactory_approvals')
            ->where('created_at < ' . $dbo->quote($limit));
        $dbo->setQuery($query);

        if (!$this->executeQuery($dbo)) {
            return false;
        }

        return true;
    }

    /**
     * Removes old profile visits.
     *
     * @return bool
     */
    protected function removeProfileVisitors()
    {
        if (!$this->settings->cron_job_profile_visitors) {
            return true;
        }

        $dbo = $this->getDbo();
        $interval = intval($this->settings->cron_job_profile_visitors) * 60 * 60 * 24;

        if (!$interval) {
            return true;
        }

        $this->logger->log('Removing profile visitors!');

        $limit = JFactory::getDate('- ' . $interval . ' seconds')->toSql();

        $query = $dbo->getQuery(true)
            ->delete()
            ->from('#__lovefactory_profile_visitors')
            ->where('date < ' . $dbo->quote($limit));
        $dbo->setQuery($query);

        if (!$this->executeQuery($dbo)) {
            return false;
        }

        return true;
    }

    /**
     * Removes old shoutbox entries.
     *
     * @return bool
     */
    protected function removeShoutBoxEntries()
    {
        if (!$this->settings->cron_job_shoutbox_messages) {
            return true;
        }

        $dbo = $this->getDbo();
        $interval = intval($this->settings->cron_job_shoutbox_messages) * 60 * 60 * 24;

        if (!$interval) {
            return true;
        }

        $this->logger->log('Removing shoutbox entries!');

        $limit = JFactory::getDate('- ' . $interval . ' seconds')->toSql();

        $query = $dbo->getQuery(true)
            ->delete()
            ->from('#__lovefactory_shoutbox')
            ->where('created_at < ' . $dbo->quote($limit));
        $dbo->setQuery($query);

        if (!$this->executeQuery($dbo)) {
            return false;
        }

        return true;
    }

    /**
     * Sends notifications to users with membership expiring soon.
     *
     * @return bool
     */
    protected function sendEndMembershipNotifications()
    {
        // Check if notification is enabled.
        if (!$this->settings->end_membership_notification) {
            return true;
        }

        $this->logger->log('Sending end membership notifications!');

        // Initialise variables.
        $date = JFactory::getDate();
        $dbo = $this->getDbo();
        $interval = $this->settings->end_membership_notify_interval;
        $limit = JFactory::getDate($date->toUnix() + $interval * 60 * 60 * 24)->toSql();

        // Get users with membership ending soon.
        $query = $dbo->getQuery(true)
            ->select('p.user_id, u.username, m.end_membership, m.id AS membership_id')
            ->from('#__lovefactory_profiles p')
            ->leftJoin('#__users u ON u.id = p.user_id')
            ->leftJoin('#__lovefactory_memberships_sold m ON m.id = p.membership_sold_id')
            ->where('m.user_id <> ' . $dbo->quote(0))
            ->where('m.end_notification = ' . $dbo->quote(0))
            ->where('m.end_membership <> ' . $dbo->quote($dbo->getNullDate()))
            ->where('m.end_membership < ' . $dbo->quote($limit));

        $results = $dbo->setQuery($query)
            ->loadObjectList();

        // Check if any members were returned.
        if (!$results) {
            return true;
        }

        // Get mailer.
        JLoader::register('FactoryMailer', LoveFactoryApplication::getInstance()->getPath('component_administrator') . DS . 'lib' . DS . 'methods.php');
        $mailer = FactoryMailer::getInstance();

        // Parse members.
        foreach ($results as $result) {
            $days_left = floor((strtotime($result->end_membership) - time()) / 60 / 60 / 24);

            // Send notification.
            $return = $mailer->send(
                'membership_ending',
                $result->user_id,
                array(
                    'receiver_username' => $result->username,
                    'days' => $days_left,
                )
            );

            // Check if notification was sent successful and mark user.
            if ($return) {
                $query = $dbo->getQuery(true)
                    ->update('#__lovefactory_memberships_sold')
                    ->set('end_notification = ' . $dbo->quote(1))
                    ->where('id = ' . $dbo->quote($result->membership_id));
                $dbo->setQuery($query);

                if (!$this->executeQuery($dbo)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Removes Activity Stream Entries marked as deleted by sender and receiver.
     *
     * @return bool
     */
    protected function removeActivityEntries()
    {
        $limit = intval($this->settings->cron_job_wallpage_entries_interval);

        if (!$limit) {
            return true;
        }

        $this->logger->log('Removing activity entries!');

        $limit = JFactory::getDate('-' . $limit . ' days')->toSql();

        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->delete()
            ->from('#__lovefactory_activity')
            ->where('(deleted_by_sender = ' . $dbo->quote(1) . ' AND deleted_by_receiver = ' . $dbo->quote(1) . ')', 'OR')
            ->where('created_at < ' . $dbo->quote($limit));
        $dbo->setQuery($query);

        if (!$this->executeQuery($dbo)) {
            return false;
        }

        return true;
    }

    protected function sendFillinNotifications()
    {
        if (!$this->settings->profile_fillin_reminder_enable) {
            return true;
        }

        require_once JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/helpers/fillin.php';

        $interval = '-' . $this->settings->profile_fillin_reminder_interval . ' days';

        $this->logger->log('Sending fillin profile notifications!');

        $dbo = JFactory::getDbo();
        $helper = new FillInNotificationHelper($dbo);

        $helper->removeObsoleteReminders();
        $helper->sendNotifications($interval);

        return true;
    }

    protected function removeTempProfilePhotos()
    {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');

        $config = JFactory::getConfig();
        $temp = $config->get('tmp_path');
        $folder = $temp . '/com_lovefactory/';

        $this->logger->log('Removing temporary profile photos!');

        JFolder::delete($folder);
        JFolder::create($folder);
    }

    /**
     * Saves the execution time and any errors for the Cron Job.
     *
     * @return bool
     */
    protected function markExecution()
    {
        $settings = JComponentHelper::getParams('com_lovefactory');

        $settings->set('cron_job.last_execution', JFactory::getDate()->toSql());
        $settings->set('cron_job.ip', $_SERVER['REMOTE_ADDR']);
        $settings->set('cron_job.errors', $this->getErrors());

        $extension = JTable::getInstance('Extension');
        $extension->load(array('type' => 'component', 'element' => 'com_lovefactory'));
        $extension->params = $settings->toString();

        if (!$extension->store()) {
            return false;
        }

        return true;
    }

    /**
     * Executes a query and sets any error message.
     *
     * @param $dbo
     * @return bool
     */
    protected function executeQuery($dbo)
    {
        $trace = debug_backtrace();
        $method = $trace[1]['function'];

        if (!$dbo->execute()) {
            $this->setError('Task ' . $method . ' failed! #' . $dbo->getErrorNum() . ' ' . $dbo->getErrorMsg());
            return false;
        }

        return true;
    }

    public function getError($i = null, $toString = true)
    {
        // Find the error
        if ($i === null) {
            // Default, return the last message
            $error = end($this->_errors);
        } elseif (!array_key_exists($i, $this->_errors)) {
            // If $i has been specified but does not exist, return false
            return false;
        } else {
            $error = $this->_errors[$i];
        }

        // Check if only the string is requested
        if ($error instanceof Exception && $toString) {
            return (string)$error;
        }

        return $error;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function setError($error)
    {
        array_push($this->_errors, $error);
    }

    /**
     * @return LoveFactoryTable
     */
    public function getTable($name = '', $prefix = 'Table', $options = array())
    {
        return parent::getTable($name, $prefix, $options); // TODO: Change the autogenerated stub
    }
}
