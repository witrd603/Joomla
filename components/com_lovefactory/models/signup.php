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

class FrontendModelSignup extends FactoryModel
{
    protected $renderErrorsIndividual = true;
    protected $context = 'com_lovefactory.profile.signup.data';

    public function getRenderer()
    {
        $renderer = LoveFactoryPageRenderer::getInstance();

        return $renderer;
    }

    public function getPage($page = 'registration', $mode = 'edit', $loadData = true)
    {
        $page = LoveFactoryPage::getInstance($page, $mode, array(
            'renderErrorsIndividual' => $this->renderErrorsIndividual
        ));

        if ($loadData) {
            $session = JFactory::getSession();

            $page->bind($session->get($this->context, null));

            $session->set($this->context, null);
        }

        return $page;
    }

    public function getSettings()
    {
        return LoveFactoryApplication::getInstance()->getSettings();
    }

    public function signup($data)
    {
        /* @var $page LoveFactoryPage */

        // If registration is disabled - Redirect to login page.
        if (JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0) {
            $this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
            return false;
        }

        $originalData = $data;

        // Initialise variables.
        $page = $this->getPage('registration', 'edit', false);

        $settings = LoveFactoryApplication::getInstance()->getSettings();
        if ($settings->registration_membership) {
            $fields = array('Price' => false, 'Gateway' => false);
            foreach ($page->getFields() as $field) {
                if ('Price' === $field->getType()) {
                    $fields['Price'] = true;
                }

                if ('Gateway' === $field->getType()) {
                    $fields['Gateway'] = true;
                }
            }

            if (!$fields['Price'] || !$fields['Gateway']) {
                return false;
            }
        }

        // Validate posted data.
        $page->bind($data);
        $valid = $page->validate();

        if (!$valid) {
            if (!$this->renderErrorsIndividual) {
                $this->setError(implode('<br />', $page->getErrors()));
            } else {
                $this->setError(FactoryText::plural('page_saving_error', count($page->getErrors())));
            }

            return false;
        }

        $data = $this->prepareData();

        // Joomla Registration
        $language = JFactory::getLanguage();
        $language->load('com_users');

        $config = JFactory::getConfig();
        $user = new JUser;
        $params = JComponentHelper::getParams('com_users');
        $data['groups'] = array($params->get('new_usertype', 2));

        $useractivation = $params->get('useractivation');
        $sendpassword = $params->get('sendpassword', 1);

        // Check if the user needs to activate their account.
        if (($useractivation == 1) || ($useractivation == 2)) {
            $data['activation'] = JApplicationHelper::getHash(JUserHelper::genRandomPassword());
            $data['block'] = 1;
        }

        // Bind the data.
        if (!$user->bind($data)) {
            /** @noinspection PhpDeprecationInspection */
            $this->setError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));
            return false;
        }

        // Load the users plugin group.
        JPluginHelper::importPlugin('user');

        // Store the data.
        if (!$user->save()) {
            /** @noinspection PhpDeprecationInspection */
            $this->setError(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $user->getError()));
            return false;
        }

        // Save Love Factory profile.
        if (!$this->saveLoveFactoryProfile($page, $user)) {
            return false;
        }

        // Compile the notification mail values.
        $data = $user->getProperties();
        $data['siteurl'] = JUri::root();
        $data['sitename'] = $config->get('sitename');

        switch ($useractivation) {
            // Self activation.
            case 1:
                $this->setState('message', FactoryText::_('signup_success_self_activation'));
                break;

            // Admin activation.
            case 2:
                $this->setState('message', FactoryText::_('signup_success_admin_activation'));
                break;

            // No activation.
            case 0:
                $this->setState('message', FactoryText::_('signup_success_no_activation'));
                break;
        }

        JEventDispatcher::getInstance()->trigger('onLoveFactoryRegistration', array(
            'com_lovefactory.registration',
            $user,
            $data,
            $useractivation
        ));

        if ($settings->registration_membership) {
            $this->setState('registration_membership', array(
                'user_id' => $user->id,
                'method' => $originalData['method'],
                'price' => $originalData['gateway'],
            ));

            JFactory::getSession()->set('registration_membership_user_id', $user->id);
        }

        return true;
    }

    public function checkUsername($data)
    {
        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('u.id')
            ->from('#__users u')
            ->where('u.username = ' . $dbo->quote($data));
        $result = $dbo->setQuery($query)
            ->loadResult();

        return $result;
    }

    public function checkEmail($data)
    {
        if (!preg_match('/^([.0-9a-z_-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i', $data)) {
            return true;
        }

        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('u.id')
            ->from('#__users u')
            ->where('u.email = ' . $dbo->quote($data));
        $result = $dbo->setQuery($query)
            ->loadResult();

        return $result;
    }

    protected function prepareData()
    {
        $data = array();
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $page = $this->getPage('registration', 'edit', false);
        $fields = $page->getFields();

        $mappings = array(
            'username' => 3,
            'email' => 12,
            'password' => 13,
            'name' => 16,
        );

        foreach ($mappings as $field => $id) {
            $property = 'registration_fields_mapping_' . $field;

            if (isset($fields[$settings->$property])) {
                $data[$field] = $fields[$settings->$property]->getData();
            }
        }

        return $data;
    }

    protected function saveLoveFactoryProfile($page, $user, $membershipSoldId = 0)
    {
        /* @var $profile TableProfile */
        $profile = $this->getTable('Profile', 'Table');
        $params = JComponentHelper::getParams('com_lovefactory');

        $profile->bindFromRequest($page->convertDataToProfile());

        $profile->user_id = $user->id;
        $profile->membership_sold_id = $membershipSoldId;
        $profile->date = JFactory::getDate()->toSql();
        $profile->online = $params->get('profile_settings.online', 0);

        $dispatcher = JEventDispatcher::getInstance();
        $dispatcher->trigger('onLoveFactoryProfileBeforeSave', array(
            'com_lovefactory.profile.save.before',
            $profile
        ));

        // Save the profile.
        if (!$profile->store()) {
            $this->setError($profile->getError());
            return false;
        }

        $page->postProfileSave($profile);

        // Update Google Maps Location based on Location fields.
        $model = JModelLegacy::getInstance('Edit', 'FrontendModel');
        $model->updateLocation($profile, false);

        return true;
    }
}
