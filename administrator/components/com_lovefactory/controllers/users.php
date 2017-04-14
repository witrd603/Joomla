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

jimport('joomla.application.component.controlleradmin');

class BackendControllerUsers extends JControllerAdmin
{
    protected $option = 'com_lovefactory';

    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->registerTask('unban', 'ban');
    }

    public function getModel($name = 'User', $prefix = 'BackendModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function ban()
    {
        $data = $this->input->get('cid', array(), 'array');
        $value = intval('ban' == $this->getTask());
        $model = $this->getModel();

        if ($model->ban($data, $value)) {
            $msg = FactoryText::_('users_task_ban_success');
        } else {
            $msg = FactoryText::_('users_task_ban_error');
        }

        $this->setRedirect(FactoryRoute::view('users'), $msg);
    }

    public function create()
    {
        $this->setRedirect('index.php?option=com_lovefactory&view=createprofile');
    }

    public function unfill()
    {
        $input = $this->input;
        $cid = $input->post->get('cid', array(), 'array');

        /** @var BackendModelUser $model */
        $model = $this->getModel();

        try {
            $model->clearFilled($cid);

            $message = FactoryText::_('users_task_unfill_success');
            $type = 'message';
        } catch (Exception $e) {
            $message = $e->getMessage();
            $type = 'error';
        }

        $this->setRedirect('index.php?option=com_lovefactory&view=users', $message, $type);
    }

    public function fill()
    {
        $input = $this->input;
        $cid = $input->post->get('cid', array(), 'array');

        /** @var BackendModelUser $model */
        $model = $this->getModel();

        try {
            $model->markFilled($cid);

            $message = FactoryText::_('users_task_fill_success');
            $type = 'message';
        } catch (Exception $e) {
            $message = $e->getMessage();
            $type = 'error';
        }

        $this->setRedirect('index.php?option=com_lovefactory&view=users', $message, $type);
    }
}
