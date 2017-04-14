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

class FrontendModelStatus extends FactoryModel
{
    public function update()
    {
        $text = JFactory::getApplication()->input->getString('text');
        $user = JFactory::getUser();
        $profile = $this->getTable('profile', 'Table');
        $settings = new LovefactorySettings();

        $text = LoveFactoryApplication::getInstance()->filterBannedWords($text);

        if (!$settings->enable_status) {
            $this->setError(FactoryText::_('status_task_update_error_not_enabled'));
            return false;
        }

        if ($user->guest) {
            $this->setError(FactoryText::_('status_task_update_error_login'));
            return false;
        }

        $profile->load($user->id);
        $profile->status = strip_tags(substr($text, 0, $settings->status_max_length));
        $profile->store();

        JEventDispatcher::getInstance()->trigger('onLoveFactoryProfileStatusChanged', array(
            'com_lovefactory.profile_status_changed',
            $profile->user_id,
            $profile->status
        ));

        $this->setState('status', $profile->status);

        return true;
    }
}
