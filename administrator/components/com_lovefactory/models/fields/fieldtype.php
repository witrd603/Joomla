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

class JFormFieldFieldType extends JFormFieldList
{
    protected $type = 'FieldType';

    protected function getInput()
    {
        $this->element['onchange'] = 'Joomla.submitbutton(\'field.update\')';

        return parent::getInput();
    }

    protected function getOptions()
    {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');

        JLoader::register('LoveFactoryField', JPATH_COMPONENT_ADMINISTRATOR . DS . 'lib' . DS . 'fields' . DS . 'field.php');

        $folders = JFolder::folders(JPATH_COMPONENT_ADMINISTRATOR . DS . 'lib' . DS . 'fields');
        $options = array();

        foreach ($folders as $folder) {
            $field = LoveFactoryField::getInstance($folder);

            $options[] = array('value' => $field->getType(), 'text' => $field->getType());
        }

        sort($options);

        array_unshift($options, array('value' => '', 'text' => ''));

        return $options;
    }
}
