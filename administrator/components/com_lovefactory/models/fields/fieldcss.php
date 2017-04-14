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

class JFormFieldFieldCSS extends JFormField
{
    protected $type = 'FieldCSS';
    protected $mode = 'css';

    protected function getInput()
    {
        $modes = array('view', 'edit', 'search');
        $html = array();
        $labels = new JRegistry($this->value);

        foreach ($modes as $mode) {
            $html[] = '<h4>' . JText::_('COM_LOVEFACTORY_FORM_FIELD_LABELS_MODE_' . strtoupper($mode)) . '</h4>';

            $id = $this->formControl . '_' . $this->getTitle() . '_' . $mode . '_enabled';
            $name = $this->formControl . '[' . $this->getTitle() . '][' . $mode . '][enabled]';
            $value = $labels->get($mode . '.enabled', 0);

            $html[] = '<label for="' . $id . '">' . JText::_('JENABLED') . '</label>';
            $html[] = JHtml::_('select.genericlist', array(JText::_('JNO'), JText::_('JYES')), $name, '', '', '', $value, $id);

            $id = $this->formControl . '_' . $this->getTitle() . '_' . $mode . '_css';
            $name = $this->formControl . '[' . $this->getTitle() . '][' . $mode . '][css]';
            $value = $labels->get($mode . '.css', '');

            $fieldType = $this->form->getValue('type');
            $fieldId = $this->form->getValue('id');

            if ('' == $value && $fieldId) {
                $field = $this->getField($fieldType, $fieldId);

                $value = '.' . $field->getContainerHtmlClass() . ' {}';
            }

            $html[] = '<label for="' . $id . '">' . JText::_('COM_LOVEFACTORY_FORM_FIELD_CSS') . '</label>';

            if ('label' == $this->mode) {
                $html[] = '<input type="text" size="60" value="' . $value . '" id="' . $id . '" name="' . $name . '">';
            } else {
                $html[] = '<textarea id="' . $id . '" name="' . $name . '" cols="50" rows="5">' . $value . '</textarea>';
            }

            $html[] = '<div class="clr"></div>';
        }

        return implode("\n", $html);
    }

    protected function getField($type, $id)
    {
        static $instances = array();

        $hash = md5($type . $id);

        if (!isset($instances[$hash])) {
            $table = JTable::getInstance('Field', 'Table');
            $table->load($id);

            $instances[$hash] = LoveFactoryField::getInstance($type, $table);
        }

        return $instances[$hash];
    }
}
