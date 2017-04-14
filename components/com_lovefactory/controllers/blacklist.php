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

class FrontendControllerBlacklist extends FrontendController
{
    public function __construct($config = array())
    {
        parent::__construct($config);

        JLoader::register('JHtmlLoveFactory', LoveFactoryApplication::getInstance()->getPath('component_site') . DS . 'lib' . DS . 'html' . DS . 'html.php');
    }

    public function add()
    {
        $model = $this->getModel('Blacklist');
        $userId = JFactory::getApplication()->input->getInt('user_id', 0);
        $response = array();

        if ($model->add($userId)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('blacklist_task_add_success');
            $response['text'] = FactoryText::_('profile_interact_unblock_user');
            $response['html'] = JHtml::_('LoveFactory.BlockButton', $userId, 1);
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('blacklist_task_add_error');
            $response['error'] = $model->getError();
            $response['redirect'] = $model->get('redirect', false);
        }

        $this->renderJson($response);
    }

    public function remove()
    {
        $model = $this->getModel('Blacklist');
        $userId = JFactory::getApplication()->input->getInt('user_id', 0);
        $response = array();

        if ($model->remove($userId)) {
            $response['status'] = 1;
            $response['message'] = FactoryText::_('blacklist_task_remove_success');
            $response['text'] = FactoryText::_('profile_interact_block_user');
            $response['html'] = JHtml::_('LoveFactory.BlockButton', $userId, 0);
        } else {
            $response['status'] = 0;
            $response['message'] = FactoryText::_('blacklist_task_remove_error');
            $response['error'] = $model->getError();
        }

        $this->renderJson($response);
    }

    public function delete()
    {
        $model = $this->getModel('Blacklist');
        $batch = JFactory::getApplication()->input->get('batch', array(), 'array');

        if ($model->remove($batch)) {
            $msg = FactoryText::_('blacklist_task_delete_success');
        } else {
            $msg = FactoryText::_('blacklist_task_delete_error');
            JFactory::getApplication()->enqueueMessage($model->getError(), 'error');
        }

        $this->setRedirect(FactoryRoute::view('blocked'), $msg);

        return true;
    }
}
