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

JFormHelper::loadFieldType('Editor');

class JFormFieldFactoryNotificationEditor extends JFormFieldEditor
{
    public $type = 'FactoryNotificationEditor';

    protected function getOptions()
    {
        $options = array();
        $type = $this->form->getValue('type');
        $xml = simplexml_load_file(LoveFactoryApplication::getInstance()->getPath('component_administrator') . DS . 'notifications.xml');
        $notification = $xml->xpath('//notification[@type="' . $type . '"]');

        if (!$notification) {
            return $options;
        }

        foreach ($notification[0]->option as $option) {
            $options['{{ ' . (string)$option . ' }}'] = 'notification_' . $type . '_' . $option;
        }

        return $options;
    }

    protected function getLabel()
    {
        if ($this->element['nolabel'] != 'false') {
            return '';
        }

        return parent::getLabel();
    }

    protected function getInput()
    {
        FactoryHtml::script('admin/fields/editor');

        $html = array();

        $html[] = '<div style="overflow: hidden; float: left; width: 100%;" class="notification-body">';
        $html[] = parent::getInput();
        $html[] = '<table class="mediamallfactoryeditor" style="float: left; clear: both; margin-top: 20px;" rel="' . $this->id . '">';

        $options = $this->getOptions();
        if ($options) {
            foreach ($this->getOptions() as $key => $text) {
                $html[] = '<tr><td width="120px">' . FactoryText::_($text) . '</td><td>-</td><td><a href="#" class="factory-notification-token">' . $key . '</a></td></tr>';
            }
        }

        $html[] = '</table>';
        $html[] = '<div>';

        return implode("\n", $html);
    }
}
