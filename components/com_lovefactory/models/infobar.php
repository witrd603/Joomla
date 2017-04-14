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

class FrontendModelInfoBar extends FactoryModel
{
    public function update()
    {
        $settings = new LovefactorySettings();

        $array = array();
        $output = array();

        if ($settings->enable_interactions) {
            $interactions = JModelLegacy::getInstance('Interactions', 'FrontendModel');
            $count = $interactions->getUnseen();

            $array['interactions'] = $count ? '<i class="factory-icon icon-counter-' . ($count <= 20 ? $count : 'more') . '"></i>' : '';
        }

        if ($settings->enable_messages) {
            $inbox = JModelLegacy::getInstance('Inbox', 'FrontendModel');
            $count = $inbox->getUnreadCount();

            $array['messages'] = $count ? '<i class="factory-icon icon-counter-' . ($count <= 20 ? $count : 'more') . '"></i>' : '';
        }

        if ($settings->enable_comments && $settings->enable_infobar_comments) {
            $model = JModelLegacy::getInstance('ItemComments', 'FrontendModel');
            $model->setItemType('Profile');
            $model->setItemId(JFactory::getUser()->id);
            $count = $model->getUnreadCount();

            $array['comments'] = $count ? '<i class="factory-icon icon-counter-' . ($count <= 20 ? $count : 'more') . '"></i>' : '';
        }

        $friends = JModelLegacy::getInstance('Friends', 'FrontendModel');
        $count = $friends->getRequests();

        $array['requests'] = $count ? '<i class="factory-icon icon-counter-' . ($count <= 20 ? $count : 'more') . '"></i>' : '';

        foreach ($array as $key => $val) {
            $output[$key] = $val;
        }

        return $output;
    }

    public function close()
    {
        $user = JFactory::getUser();

        if ($user->guest) {
            return false;
        }

        $profile = JTable::getInstance('Profile', 'Table');
        $profile->load($user->id);
        $params = new \Joomla\Registry\Registry($profile->params);
        $params->set('infobar', 0);

        $profile->params = $params->toString();

        return $profile->store();
    }
}
