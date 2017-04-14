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

use ThePhpFactory\LoveFactory\Factory;

class FrontendViewEdit extends FactoryView
{
    protected
        $get = array(
        'profile',
        'page',
        'renderer',
        'settings',
        'isAllowedToUpdate'
    );

    public function display($tpl = null)
    {
        $key = LoveFactoryApplication::getInstance()->getSettings('gmaps_api_key', '');
        if ($key) {
            JHtml::script('https://maps.googleapis.com/maps/api/js?key=' . $key);
        }

        parent::display($tpl);

        $profile = $this->get('profile');

        if ($profile->isPending) {
            JFactory::getApplication()->enqueueMessage(FactoryText::_('edit_profile_approval_profile_waiting_approval'), 'notice');
        } elseif ($profile->isDraft) {
            JFactory::getApplication()->enqueueMessage(FactoryText::_('edit_profile_approval_profile_submit_profile_for_approval'), 'notice');
        }
    }

    protected function getRenderer()
    {
        return Factory::buildPageRenderer();
    }

    protected function getSettings()
    {
        return LoveFactoryApplication::getInstance()->getSettings();
    }
}
