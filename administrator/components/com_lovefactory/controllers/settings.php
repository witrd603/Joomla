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

class BackendControllerSettings extends BackendController
{
    function __construct()
    {
        parent::__construct();

        $this->registerTask('apply', 'save');
    }

    function save()
    {
        $model = $this->getModel('settings');

        if ($model->store()) {
            $msg = JText::_('Settings Saved!');
        } else {
            $msg = JText::_('Error Saving Settings');
        }

        $tab = 0;

        if ('apply' == $this->getTask()) {
            $tab = JFactory::getApplication()->input->getInt('selected_tab');
        }

        $modal = JFactory::getApplication()->input->getInt('modal');

        if ($modal) {
            if ('apply' != $this->getTask()) {
                JFactory::getDocument()->addScriptDeclaration('window.parent.SqueezeBox.close();');
            } else {
                $this->setRedirect($_SERVER['HTTP_REFERER'], $msg);
            }
        } else {
            if ('apply' == $this->getTask()) {
                $layout = JFactory::getApplication()->input->getCmd('layout');
                $this->setRedirect('index.php?option=com_lovefactory&view=settings&tab=' . $tab . '&layout=' . $layout, $msg);
            } else {
                $this->setRedirect('index.php?option=com_lovefactory&view=configuration', $msg);
            }
        }
    }

    function cancel()
    {
        $this->setRedirect('index.php?option=com_lovefactory&task=configuration');
    }

    function emptyShoutboxLog()
    {
        $log = JPATH_COMPONENT_ADMINISTRATOR . DS . 'shoutbox_log.txt';

        file_put_contents($log, '');

        $msg = JText::_('Log empty!');
        $this->setRedirect('index.php?option=com_lovefactory&task=settings', $msg);
    }

    public function log()
    {
        $contents = @file_get_contents(JPATH_ADMINISTRATOR . '/components/com_lovefactory/cron_log.php');

        echo nl2br($contents);
    }

    public function clearLog()
    {
        if (file_exists(JPATH_ADMINISTRATOR . '/components/com_lovefactory/cron_log.php')) {
            unlink(JPATH_ADMINISTRATOR . '/components/com_lovefactory/cron_log.php');
        }

        JFactory::getApplication()->redirect('index.php?option=com_lovefactory&view=settings&tab=&layout=#cron');
    }
}
