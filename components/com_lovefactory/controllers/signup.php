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

class FrontendControllerSignup extends FrontendController
{
    public function signup()
    {
        // If registration is disabled - Redirect to login page.
        if (JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0) {
            $this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
            return false;
        }

        $model = $this->getModel('Signup', 'FrontendModel');
        $session = JFactory::getSession();
        $context = 'com_lovefactory.profile.signup.data';

        $data = JFactory::getApplication()->input->post->get('form', array(), 'array');
        $files = JFactory::getApplication()->input->files->get('form', array());
        $data = array_merge($data, $files);

        $session->set($context, null);

        if ($model->signup($data)) {
            $settings = LoveFactoryApplication::getInstance()->getSettings();
            $msg = FactoryText::_('signup_task_signup_success');

            if ($settings->registration_membership) {
                $data = $model->getState('registration_membership');
                $link = FactoryRoute::task('gateway.process&method=' . $data['method'] . '&price=' . $data['price'] . '&step=1&user_id=' . $data['user_id']);
            } else {
                $link = JMenu::getInstance('site')->getDefault()->link;
            }

            JFactory::getApplication()->enqueueMessage($model->getState('message'));
        } else {
            $msg = FactoryText::_('signup_task_signup_error');
            $link = FactoryRoute::view('signup');
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');

            $session->set($context, $data);
        }

        $this->setRedirect($link, $msg);
    }

    public function checkUsername()
    {
        $data = JFactory::getApplication()->input->getString('value', '');
        $model = $this->getModel('Signup');
        $result = $model->checkUsername($data);
        $response = array('status' => !$result);

        $this->renderJson($response);

        return true;
    }

    public function checkEmail()
    {
        $data = JFactory::getApplication()->input->getString('value', '');
        $model = $this->getModel('Signup');
        $result = $model->checkEmail($data);

        $response = array(
            'status' => !$result,
        );

        $this->renderJson($response);

        return true;
    }

    public function priceUpdate()
    {
        /** @var FrontendModelMembershipBuy $model */

        $gender = JFactory::getApplication()->input->get('gender', '');
        $model = JModelLegacy::getInstance('MembershipBuy', 'FrontendModel');

        echo $model->getPriceSelect('form[gateway]', null, $gender);

        jexit();
    }
}
