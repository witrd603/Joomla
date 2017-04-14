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

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('List');

class JFormFieldLoveFactoryProfileOnlineStatus extends JFormFieldList
{
    public $type = 'LoveFactoryProfileOnlineStatus';

    protected function getOptions()
    {
        $enabled = LoveFactoryApplication::getInstance()->getSettings('enable_friends');

        $options = array(
            0 => JText::_('COM_LOVEFACTORY_FORM_SETTINGS_ONLINE_OPTION_ONLINE'),
            1 => JText::_('COM_LOVEFACTORY_FORM_SETTINGS_ONLINE_OPTION_FRIENDS'),
            2 => JText::_('COM_LOVEFACTORY_FORM_SETTINGS_ONLINE_OPTION_OFFLINE'),
        );

        if (!$enabled) {
            unset($options[1]);
        }

        return $options;
    }
}
