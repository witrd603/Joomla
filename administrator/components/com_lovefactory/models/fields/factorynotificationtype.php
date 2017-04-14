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

JFormHelper::loadFieldType('List');

class JFormFieldFactoryNotificationType extends JFormFieldList
{
    public $type = 'FactoryNotificationType';

    protected function getOptions()
    {
        $options = array();
        $xml = simplexml_load_file(LoveFactoryApplication::getInstance()->getPath('component_administrator') . DS . 'notifications.xml');

        foreach ($xml->notification as $notification) {
            $options[(string)$notification->attributes()->type] = FactoryText::_('notification_' . (string)$notification->attributes()->type);
        }

        array_unshift($options, array('value' => '', 'text' => ''));

        return $options;
    }
}
