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

jimport('joomla.plugin.plugin');
jimport('joomla.application.component.model');

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

class plgUserLoveFactoryUser extends JPlugin
{
    public function onUserAfterDelete($user, $succes, $msg)
    {
        if (!$succes || !$user['id']) {
            return false;
        }

        $filepath = JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_lovefactory' . DS . 'settings.php';

        if (!file_exists($filepath)) {
            return false;
        }

        require_once($filepath);
        $settings = new LovefactorySettings();

        if (!$settings->delete_user_plugin) {
            return true;
        }

        JTable::addIncludePath(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_lovefactory' . DS . 'tables');
        $table = JTable::getInstance('Profile', 'Table');

        $table->load($user['id']);

        if ($table->user_id) {
            $table->delete();
        }

        return true;
    }

    public function onUserLogin($user, $options)
    {
        $filepath = JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_lovefactory' . DS . 'settings.php';

        if (!file_exists($filepath)) {
            return false;
        }

        $model = $this->getModel();

        $model->login($user['username']);

        return true;
    }

    public function onUserLogout($user)
    {
        $filepath = JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_lovefactory' . DS . 'settings.php';

        if (!file_exists($filepath)) {
            return false;
        }

        $model = $this->getModel();

        $model->logout($user['username']);

        return true;
    }

    public function onUserAfterSave($user, $isNew, $success, $msg)
    {
        if (!$isNew) {
            return null;
        }

        /** @var FrontendModelProfile $model */
        $model = $this->getModel();
        $model->createProfileFromJoomlaUser($user);

        // Set up fill in reminder notification.
        JLoader::register('LoveFactoryApplication', JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/application.php');
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if ($settings->profile_fillin_reminder_enable) {
            $this->getFillInHelper()->setReminder($user['id']);
        }

        return true;
    }

    protected function getModel()
    {
        JModelLegacy::addIncludePath(JPATH_SITE . DS . 'components' . DS . 'com_lovefactory' . DS . 'models');
        $model = JModelLegacy::getInstance('Profile', 'FrontendModel');

        return $model;
    }

    /**
     * @return FillInNotificationHelper
     */
    protected function getFillInHelper()
    {
        static $helper = null;

        if (null === $helper) {
            require_once JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/helpers/fillin.php';

            $dbo = JFactory::getDbo();

            $helper = new FillInNotificationHelper($dbo);
        }

        return $helper;
    }
}
