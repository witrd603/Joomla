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

class JFormFieldLoveFactoryPrivacyList extends JFormFieldList
{
    public $type = 'LoveFactoryPrivacyList';

    protected function getOptions()
    {
        $enabled = LoveFactoryApplication::getInstance()->getSettings('enable_friends');

        $options = array(
            'public' => FactoryText::_('settings_privacy_level_public'),
            'friends' => FactoryText::_('settings_privacy_level_friends'),
            'private' => FactoryText::_('settings_privacy_level_private'),
        );

        if (!$enabled) {
            unset($options['friends']);
        }

        return $options;
    }
}
