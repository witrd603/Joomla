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

class FrontendControllerProfile extends FrontendController
{
    public function update()
    {
        $model = $this->getModel('Edit', 'FrontendModel');
        $data = JFactory::getApplication()->input->post->get('form', array(), 'array');
        $files = JFactory::getApplication()->input->files->get('form', array());
        $data = array_merge($data, $files);
        $session = JFactory::getSession();
        $context = 'com_lovefactory.profile.edit.data';

        $session->set($context, null);

        if ($model->update($data)) {
            $msg = FactoryText::_('profile_task_update_success');
        } else {
            $msg = FactoryText::_('profile_task_update_error');
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');

            $session->set($context, $model->getState('filtered.data', null));
        }

        $this->setRedirect(FactoryRoute::view('edit'), $msg);
    }

    public function submitApproval()
    {
        $model = $this->getModel('Edit', 'FrontendModel');
        $user_id = JFactory::getUser()->id;

        if ($model->submitForApproval($user_id)) {
            $msg = FactoryText::_('profile_task_submit_approval_success');
        } else {
            $msg = FactoryText::_('profile_task_submit_approval_error');
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
        }

        $this->setRedirect(FactoryRoute::view('edit'), $msg);
    }

    public function restore()
    {
        $model = $this->getModel('Edit', 'FrontendModel');
        $user_id = JFactory::getUser()->id;

        if ($model->restore($user_id)) {
            $msg = FactoryText::_('profile_task_restore_success');
        } else {
            $msg = FactoryText::_('profile_task_restore_error');
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
        }

        $this->setRedirect(FactoryRoute::view('edit'), $msg);
    }

    public function create()
    {
        $model = $this->getModel('CreateProfile', 'FrontendModel');
        $session = JFactory::getSession();
        $context = 'com_lovefactory.profile.create.data';

        $data = $this->input->post->get('form', array(), 'array');
        $files = $this->input->files->get('form', array());
        $data = array_merge($data, $files);

        $session->set($context, null);

        if ($model->create($data)) {
            $msg = FactoryText::_('profile_create_task_signup_success');
            $link = FactoryRoute::view('createprofile');
            JFactory::getApplication()->enqueueMessage($model->getError());
        } else {
            $msg = FactoryText::_('profile_create_task_signup_error');
            $link = FactoryRoute::view('createprofile');
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');

            $session->set($context, $data);
        }

        $this->setRedirect($link, $msg);

        return true;
    }

    public function delete()
    {
        $user = JFactory::getUser();
        $app = JFactory::getApplication();
        $profile = JTable::getInstance('Profile', 'Table');

        try {
            if (!JSession::checkToken()) {
                throw new Exception(JText::_('JINVALID_TOKEN'));
            }

            $profile->delete($user->id);
            $user->delete();

            $url = FactoryRoute::view('delete&layout=complete');
        } catch (Exception $e) {
            $url = FactoryRoute::view('delete');
            $app->enqueueMessage(FactoryText::_('delete_profile_task_error'), 'error');
            $app->enqueueMessage($e->getMessage(), 'error');
        }

        $this->setRedirect($url);
    }
}
