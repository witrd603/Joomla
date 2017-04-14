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

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

class plgSystemLoveFactory extends JPlugin
{
    public function onAfterRoute()
    {
        // Initialise variables.
        $settings = $this->getSettings();
        $user = JFactory::getUser();

        // Check if component is installed or user is guest or is ajax request or we're on the backend.
        if (!$this->isComponentInstalled() ||
            $this->isBackend() ||
            $this->isAjaxRequest()
        ) {
            return false;
        }

        $app = JFactory::getApplication();

        $view = JFactory::getApplication()->input->getString('view', '');
        $task = JFactory::getApplication()->input->getString('task', '');
        $option = JFactory::getApplication()->input->getString('option', '');
        $layout = JFactory::getApplication()->input->getString('layout', '');

        // Check for registration page
        if ('com_users' == $option &&
            ('registration' == $view && !in_array($task, array('user.login', 'reset.request', 'remind.remind', 'reset.confirm', 'reset.complete'))) &&
            'complete' != $layout &&
            1 == $settings->registration_mode
        ) {
            $app->redirect(JRoute::_('index.php?option=com_lovefactory&view=signup&Itemid=' . $this->params->get('itemid', 0), false));
        }

        // Check for login page
        $settings->registration_login_redirect = isset($settings->registration_login_redirect) ? $settings->registration_login_redirect : 0;
        if ('com_users' == $option && 'login' == $view && $settings->registration_login_redirect) {
            $params = $app->getParams('com_users');
            $params->set('login_redirect_url', $this->getRedirectUrl());
        }
    }

    public function onAfterDispatch()
    {
        // Initialise variables.
        $settings = $this->getSettings();
        $user = JFactory::getUser();

        // Set the height and width for the thumbnails.
        if ($this->isComponentInstalled() ||
            !$this->isBackend() ||
            !$this->isAjaxRequest()
        ) {
            $document = JFactory::getDocument();
            $document->addStyleDeclaration('.lovefactory-thumbnail { width: ' . $settings->thumbnail_max_width . 'px; height: ' . $settings->thumbnail_max_width . 'px; }');
        }

        $params = JComponentHelper::getParams('com_lovefactory');

        // Check if component is installed or user is guest or is ajax request or we're on the backend.
        if (!$this->isComponentInstalled() ||
            $user->guest ||
            !$params->get('infobar.enabled', 1) ||
            $this->isBackend() ||
            $this->isAjaxRequest() ||
            'component' == JFactory::getApplication()->input->getCmd('tmpl', '')
        ) {
            return false;
        }

        // Handle flash message.
        JLoader::register('FactoryFlashMessage', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'lib' . DS . 'methods.php');
        $flash = FactoryFlashMessage::getInstance();
        $flash->render();

        // Get user infobar location.
        $location = $this->getUserInforbarLocation();

        // Check if user has enabled infobar.
        if (!$location) {
            return false;
        }

        // Load language file
        $language = JFactory::getLanguage();
        $language->load('com_lovefactory');

        // Render the infobar.
        JLoader::register('JHtmlLoveFactory', JPATH_SITE . DS . 'components' . DS . 'com_lovefactory' . DS . 'lib' . DS . 'html' . DS . 'html.php');
        JLoader::register('LoveFactoryApplication', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'lib' . DS . 'application.php');

        JModelLegacy::addIncludePath(JPATH_SITE . DS . 'components' . DS . 'com_lovefactory' . DS . 'models');
        $model = JModelLegacy::getInstance('Infobar', 'FrontendModel');
        $data = $model->update();

        return JHtml::_('LoveFactory.infobar', $settings, $location, $data);
    }

    public function onBeforeCompileHead()
    {
        $this->reorderStylesheets();
        $this->reorderScripts();
    }

    public function onLoveFactoryProfileBeforeSave($context, $profile, $nameFieldId = null, $surnameFieldId = null, $username = null)
    {
        if ('com_lovefactory.profile.save.before' != $context &&
            'com_lovefactory.settings.save.before' != $context
        ) {
            return true;
        }

        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if (null === $nameFieldId) {
            $nameFieldId = $settings->display_user_name[0];
        }

        if (null === $surnameFieldId) {
            $surnameFieldId = $settings->display_user_name[1];
        }

        if (null === $username) {
            $username = JFactory::getUser($profile->user_id)->username;
        }

        $profile->display_name = $this->getDisplayName($profile, $nameFieldId, $surnameFieldId, $username);

        return true;
    }

    public function onLoveFactoryBackendProfileSave($context, $profileId, array $data = array())
    {
        if ('com_lovefactory.backend.profile.save.after' !== $context) {
            return true;
        }

        if (isset($data['membership']) && '' !== $data['membership']['membership']) {
            $membershipId = (int)$data['membership']['membership'];
            $expiration = $data['membership']['until'];

            /** @var TableProfile $profile */
            $profile = JTable::getInstance('Profile', 'Table');
            $profile->load($profileId);

            /** @var BackendModelUserMembership $model */
            JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_lovefactory/models');
            $model = JModelLegacy::getInstance('UserMembership', 'BackendModel');
            $model->manualUpdate($profile, $membershipId, $expiration);
        }

        return true;
    }

    public function onLoveFactoryUserMembershipChange($context, $profile, $membership)
    {
        if ('com_lovefactory.user.membership_change' !== $context) {
            return true;
        }

        require_once JPATH_SITE . '/components/com_lovefactory/vendor/autoload.php';

        $restrictions = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::getAdjustableRestrictionTypes();
        $params = new Joomla\Registry\Registry($membership->restrictions);

        foreach ($restrictions as $restriction) {
            $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction($restriction);
            $newValue = $params->get($restriction->getRestrictionName());

            $restriction->adjustResources($newValue, $profile->user_id);
        }

        return true;
    }

    public function onLoveFactoryUserMembershipUpdated($context, $userIds, $restrictions)
    {
        if ('com_lovefactory.user.membership_updated' !== $context) {
            return true;
        }

        require_once JPATH_SITE . '/components/com_lovefactory/vendor/autoload.php';

        $restrictionTypes = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::getAdjustableRestrictionTypes();
        $params = new Joomla\Registry\Registry($restrictions);

        foreach ($userIds as $userId) {
            foreach ($restrictionTypes as $restrictionType) {
                $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction($restrictionType);
                $newValue = $params->get($restriction->getRestrictionName());

                $restriction->adjustResources($newValue, $userId);
            }
        }

        return true;
    }

    public function onLoveFactoryMembershipUpdated($context, $membership, $applyToSold)
    {
        if ('com_lovefactory.membership.updated' !== $context) {
            return true;
        }

        if (!$applyToSold) {
            return true;
        }

        $dbo = JFactory::getDbo();
        $table = JTable::getInstance('MembershipSold', 'Table');
        $id = $membership->id;
        $restrictions = $membership->restrictions;

        $query = $dbo->getQuery(true)
            ->select('m.user_id')
            ->from($dbo->qn($table->getTableName(), 'm'))
            ->where('m.membership_id = ' . $dbo->q($id))
            ->where('m.restrictions <> ' . $dbo->q($restrictions))
            ->where('m.expired = ' . $dbo->q(0));
        $results = $dbo->setQuery($query)
            ->loadAssocList('user_id');

        $userIds = array_keys($results);

        if (!$userIds) {
            return true;
        }

        $query = $dbo->getQuery(true)
            ->update($dbo->qn($table->getTableName()))
            ->set('restrictions = ' . $dbo->q($restrictions))
            ->where('membership_id = ' . $dbo->q($id))
            ->where('restrictions <> ' . $dbo->q($restrictions))
            ->where('expired = ' . $dbo->q(0));

        $dbo->setQuery($query)
            ->execute();

        JEventDispatcher::getInstance()->trigger('onLoveFactoryUserMembershipUpdated', array(
            'com_lovefactory.user.membership_updated', $userIds, $restrictions,
        ));

        return true;
    }

    public function onLoveFactoryPaymentUpdated($context, $payment, $statusChanged)
    {
        if ('com_lovefactory.payment.updated' !== $context) {
            return true;
        }

        if ($statusChanged) {
            /** @var TableOrder $order */
            $order = JTable::getInstance('Order', 'Table');
            $order->load($payment->order_id);

            if ($order->id) {
                $order->updateFromPaymentStatus($payment->status);
            }
        }

        return true;
    }

    public function onLoveFactoryOrderCompleted($context, $order)
    {
        if ('com_lovefactory.order_completed' !== $context) {
            return true;
        }

        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_lovefactory/models');
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        // 1. Issue invoice.
        /** @var BackendModelInvoice $model */
        $model = JModelLegacy::getInstance('Invoice', 'BackendModel');
        $model->issue($settings, $order);

        // 2. Update user profile with new membership.
        /** @var TableProfile $profile */
        $profile = JTable::getInstance('Profile', 'Table');
        $profile->load($order->user_id);

        /** @var BackendModelUserMembership $model */
        $model = JModelLegacy::getInstance('UserMembership', 'BackendModel');
        $model->orderUpdate($profile, $order);

        return true;
    }

    public function onBlogFactoryBeforeCreateBlog($context, $userId)
    {
        if ('com_blogfactory.create_blog.before' !== $context) {
            return true;
        }

        require_once JPATH_ADMINISTRATOR . '/components/com_lovefactory/settings.php';

        $settings = new LovefactorySettings();

        if (!$settings->enable_blogfactory_integration) {
            return true;
        }

        require_once JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/application.php';
        require_once JPATH_SITE . '/components/com_lovefactory/vendor/autoload.php';
        JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_lovefactory/tables');
        JFactory::getLanguage()->load('com_lovefactory', JPATH_SITE);

        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('blog_factory_access');

        try {
            $restriction->isAllowed($userId);
        } catch (Exception $e) {
            return array(false, $e->getMessage());
        }

        return true;
    }

    public function onChatFactoryBeforeStartChat($context, $userId)
    {
        if ('com_chatfactory.start_chat.before' !== $context) {
            return true;
        }

        require_once JPATH_ADMINISTRATOR . '/components/com_lovefactory/settings.php';

        $settings = new LovefactorySettings();

        if (!$settings->enable_blogfactory_integration) {
            return true;
        }

        require_once JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/application.php';
        require_once JPATH_SITE . '/components/com_lovefactory/vendor/autoload.php';
        JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_lovefactory/tables');
        JFactory::getLanguage()->load('com_lovefactory', JPATH_SITE);

        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('chat_factory_access');

        try {
            $restriction->isAllowed($userId);
        } catch (Exception $e) {
            return array(false, $e->getMessage());
        }

        return true;
    }

    public function onUserRemoved($context, $user)
    {
        if ('com_lovefactory' !== $context) {
            return null;
        }

        /** @var $model BackendModelUserDelete */
        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_lovefactory/models/');
        $model = JModelLegacy::getInstance('UserDelete', 'BackendModel');

        $model->deleteUserDependencies($user);

        return true;
    }

    public function onAfterClearedFilledProfiles($context, $profiles)
    {
        if ('com_lovefactory' !== $context) {
            return null;
        }

        if (is_integer($profiles)) {
            $profiles = array($profiles);
        }

        require_once JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/helpers/fillin.php';

        $dbo = JFactory::getDbo();
        $helper = new FillInNotificationHelper($dbo);

        $helper->setReminders($profiles);

        return true;
    }

    public function onAfterMarkedFilledProfiles($context)
    {
        if ('com_lovefactory' !== $context) {
            return null;
        }

        require_once JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/helpers/fillin.php';

        $dbo = JFactory::getDbo();
        $helper = new FillInNotificationHelper($dbo);

        $helper->removeObsoleteReminders();

        return true;
    }

    protected function isAjaxRequest()
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) || 'raw' == JFactory::getApplication()->input->getCmd('format');
    }

    protected function getRedirectUrl()
    {
        JLoader::register('LoveFactoryApplication', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'lib' . DS . 'application.php');

        $app = JFactory::getApplication();
        $router = $app->getRouter();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        $query->select($db->quoteName('link'));
        $query->from($db->quoteName('#__menu'));
        $query->where($db->quoteName('published') . '=1');
        $query->where($db->quoteName('id') . '=' . $db->quote($settings->registration_login_redirect));

        $db->setQuery($query);
        if ($link = $db->loadResult()) {
            if ($router->getMode() == JROUTER_MODE_SEF) {
                $url = 'index.php?Itemid=' . $settings->registration_login_redirect;
            } else {
                $url = $link . '&Itemid=' . $settings->registration_login_redirect;
            }
        }

        return $url;
    }

    protected function isComponentInstalled()
    {
        static $installed = null;

        if (is_null($installed)) {
            $table = JTable::getInstance('Extension');
            $result = $table->find(array('element' => 'com_lovefactory', 'type' => 'component'));

            $installed = (boolean)$result;
        }

        return $installed;
    }

    protected function getSettings()
    {
        static $settings = null;

        if (is_null($settings)) {
            $filepath = JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'settings.php';

            jimport('joomla.filesystem.file');

            if (!JFile::exists($filepath)) {
                $settings = new stdClass();
            } else {
                require_once $filepath;
                $settings = new LovefactorySettings();
            }
        }

        return $settings;
    }

    protected function getUserInforbarLocation()
    {
        JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'tables');

        // Initialise variables.
        $user = JFactory::getUser();
        $dbo = JFactory::getDbo();
        $settings = $this->getSettings();

        $profile = JTable::getInstance('Profile', 'Table');
        $profile->load($user->id);

        $settings = JComponentHelper::getParams('com_lovefactory');
        $profile->setSettings($settings);

        $result = $profile->getParameter('infobar');

        return $result;
    }

    protected function isBackend()
    {
        return JFactory::getApplication()->isAdmin();
    }

    protected function reorderStylesheets()
    {
        $document = JFactory::getDocument();
        $temp = array();

        foreach ($document->_styleSheets as $path => $stylesheet) {
            if (false !== strpos($path, 'components/com_lovefactory/assets/css')) {
                $temp[$path] = $stylesheet;
                unset($document->_styleSheets[$path]);
            }
        }

        foreach ($temp as $path => $stylesheet) {
            $document->_styleSheets[$path] = $stylesheet;
        }

        return true;
    }

    protected function reorderScripts()
    {
        $document = JFactory::getDocument();
        $temp = array();
        $framework = array();

        // Get component scripts.
        foreach ($document->_scripts as $path => $script) {
            if (false !== strpos($path, 'components/com_lovefactory/assets/js')) {

                if (false !== strpos($path, 'components/com_lovefactory/assets/js/jquery.js')) {
                    $framework['jquery'] = array($path => $script);
                } elseif (false !== strpos($path, 'components/com_lovefactory/assets/js/jquery.noconflict.js')) {
                    $framework['noconflict'] = array($path => $script);
                } else {
                    $temp[$path] = $script;
                }

                unset($document->_scripts[$path]);
            }
        }

        // Add framework files.
        if (isset($framework['jquery'])) {
            $temp = array_merge($framework['jquery'], $temp);
            $temp = array_merge($temp, $framework['noconflict']);
        }

        foreach ($temp as $path => $script) {
            $document->_scripts[$path] = $script;
        }

        return true;
    }

    protected function getDisplayName($profile, $nameFieldId, $surnameFieldId, $username)
    {
        $displayName = array();

        if ($nameFieldId && isset($profile->{'field_' . $nameFieldId})) {
            $displayName[] = $profile->{'field_' . $nameFieldId};
        }

        if ($surnameFieldId && isset($profile->{'field_' . $surnameFieldId})) {
            $displayName[] = $profile->{'field_' . $surnameFieldId};
        }

        if (!$displayName) {
            return $username;
        }

        return implode(' ', $displayName);
    }
}
