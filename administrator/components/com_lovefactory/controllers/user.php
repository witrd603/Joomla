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

jimport('joomla.application.component.controllerform');

class BackendControllerUser extends JControllerForm
{
    protected $option = 'com_lovefactory';

    public function save($key = null, $urlVar = null)
    {
        if (!parent::save($key, $urlVar)) {
            return false;
        }

        $model = $this->getModel('User');
        $userId = JFactory::getApplication()->input->getInt('user_id', 0);
        $videos = JFactory::getApplication()->input->get('videos', array(), 'array');
        $photos = JFactory::getApplication()->input->get('photos', array(), 'array');

        $model->deleteVideos($userId, $videos);
        $model->deletePhotos($userId, $photos);

        return true;
    }

    public function create()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if (!array_intersect(JFactory::getUser()->groups, $settings->create_profile_admin_groups)) {
            JFactory::getApplication()->enqueueMessage('You are not allowed to access that page!');
            JFactory::getApplication()->redirect('index.php?option=com_lovefactory');
        }

        require_once JPATH_SITE . '/components/com_lovefactory/vendor/autoload.php';
        JFactory::getLanguage()->load('com_lovefactory', JPATH_SITE);
        JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_lovefactory/models');

        $model = JModelLegacy::getInstance('CreateProfile', 'FrontendModel');
        $session = JFactory::getSession();
        $context = 'com_lovefactory.profile.create.data';

        $data = $this->input->post->get('form', array(), 'array');
        $files = $this->input->files->get('form', array());
        $data = array_merge($data, $files);

        $session->set($context, null);

        if ($model->create($data)) {
            $msg = FactoryText::_('profile_create_task_signup_success');
            $link = 'index.php?option=com_lovefactory&view=users';
            JFactory::getApplication()->enqueueMessage($model->getError());
        } else {
            $msg = FactoryText::_('profile_create_task_signup_error');
            $link = 'index.php?option=com_lovefactory&view=createprofile';
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');

            $session->set($context, $data);
        }

        $this->setRedirect($link, $msg);

        return true;
    }

    public function saveProfile()
    {
        require_once JPATH_SITE . '/components/com_lovefactory/vendor/autoload.php';
        JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_lovefactory/models');

        $model = $this->getModel('Edit', 'FrontendModel');
        $data = JFactory::getApplication()->input->post->get('form', array(), 'array');
        $files = JFactory::getApplication()->input->files->get('form', array());
        $data = array_merge($data, $files);
        $session = JFactory::getSession();
        $context = 'com_lovefactory.profile.edit.data';
        $userId = $this->input->getInt('user_id');

        JFactory::getLanguage()->load('com_lovefactory', JPATH_SITE);

        if ($model->update($data, $userId)) {
            $msg = FactoryText::_('profile_task_update_success');
        } else {
            $msg = FactoryText::_('profile_task_update_error');
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');

            $session->set($context, $model->getState('filtered.data', null));
        }

        $this->setRedirect('index.php?option=com_lovefactory&view=user&layout=edit&mode=editable&user_id=' . $userId, $msg);
    }

    public function activateMembership()
    {
        $id = $this->input->getInt('id');
        $model = JModelLegacy::getInstance('UserMembership', 'BackendModel');

        $model->activateExpiredMembership($id);

        $userId = $model->getState('user_id');

        $msg = 'Membership marked as active!';
        $this->setRedirect('index.php?option=com_lovefactory&view=user&user_id=' . $userId, $msg);
    }
}
