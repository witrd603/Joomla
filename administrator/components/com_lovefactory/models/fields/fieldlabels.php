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

class JFormFieldFieldLabels extends JFormField
{
    protected $type = 'FieldLabels';
    protected $mode = 'label';

    protected function getInput()
    {
        $modes = array('view', 'edit', 'search');
        $languages = JLanguageHelper::getLanguages();
        $html = array();
        $labels = new JRegistry($this->value);

        array_unshift($languages, (object)array('title' => JText::_('JDEFAULT'), 'lang_code' => 'default'));

        foreach ($modes as $mode) {
            $html[] = '<h4>' . JText::_('COM_LOVEFACTORY_FORM_FIELD_LABELS_MODE_' . strtoupper($mode)) . '</h4>';

            $id = $this->formControl . '_' . $this->getTitle() . '_' . $mode . '_enabled';
            $name = $this->formControl . '[' . $this->getTitle() . '][' . $mode . '][enabled]';
            $value = $labels->get($mode . '.enabled', 1);

            $html[] = '<label for="' . $id . '">' . JText::_('JENABLED') . '</label>';
            $html[] = JHtml::_('select.genericlist', array(JText::_('JNO'), JText::_('JYES')), $name, '', '', '', $value, $id);

            foreach ($languages as $language) {
                $id = $this->formControl . '_' . $this->getTitle() . '_' . $mode . '_' . $language->lang_code;
                $name = $this->formControl . '[' . $this->getTitle() . '][' . $mode . '][' . $language->lang_code . ']';
                $value = $labels->get($mode . '.' . $language->lang_code, '');

                $html[] = '<label for="' . $id . '">' . $language->title . '</label>';

                if ('label' == $this->mode) {
                    $html[] = '<input type="text" size="60" value="' . $value . '" id="' . $id . '" name="' . $name . '">';
                } else {
                    $html[] = '<textarea id="' . $id . '" name="' . $name . '" cols="50" rows="5">' . $value . '</textarea>';
                }
            }

            $html[] = '<div class="clr"></div>';
        }

        return implode("\n", $html);
    }
}
