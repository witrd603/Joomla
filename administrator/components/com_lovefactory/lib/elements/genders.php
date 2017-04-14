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

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldGenders extends JFormField
{
    protected function getInput()
    {
        $output = JHTML::_(
            'select.genericlist',
            $this->getOptions(),
            $this->name . '[]',
            'class="inputbox" size="8" multiple="multiple"',
            'id',
            'title',
            $this->value
        );

        return $output;
    }

    private function getOptions()
    {
        JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'tables');
        JLoader::register('LoveFactoryField', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'lib' . DS . 'fields' . DS . 'field.php');
        JLoader::register('LoveFactoryApplication', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'lib' . DS . 'application.php');
        $table = JTable::getInstance('Field', 'Table');
        $table->load(array('type' => 'Gender'));

        $field = LoveFactoryField::getInstance($table->type, $table);

        return $field->getChoices();
    }
}
