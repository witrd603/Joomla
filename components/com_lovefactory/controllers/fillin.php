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

class FrontendControllerFillin extends FrontendController
{
    public function fillin()
    {
        JSession::checkToken() or die('Invalid Token');

        $model = $this->getModel('Fillin', 'FrontendModel');
        $data = JFactory::getApplication()->input->post->get('form', array(), 'array');
        $files = JFactory::getApplication()->input->files->get('form', array());
        $data = array_merge($data, $files);
        $session = JFactory::getSession();
        $context = 'com_lovefactory.profile.fillin.data';

        $session->set($context, null);

        if ($model->fillin($data)) {
            $msg = FactoryText::_('fillin_task_fillin_success');
            $link = FactoryRoute::view('profile');
        } else {
            $msg = FactoryText::_('fillin_task_fillin_error');
            $link = FactoryRoute::view('fillin');

            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
            $session->set($context, $model->getState('filtered.data', null));
        }

        $this->setRedirect($link, $msg);

        return true;
    }
}
