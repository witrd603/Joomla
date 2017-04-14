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

class JFormFieldGenderOppositeField extends JFormFieldText
{
    public $type = 'GenderOppositeField';

    protected function getInput()
    {
        $choices = $this->form->getValue('choices', 'params');

        if (!isset($choices['default']) || !$choices['default']) {
            return 'You must first define the default choices and save the field!';
        }

        $html = array();

        $html[] = '<div>';

        foreach ($choices['default'] as $id => $label) {
            $name = $this->name . '[' . $id . ']';
            $selected = isset($this->value[$id]) ? $this->value[$id] : '';

            $html[] = '<div>';

            $html[] = '<label>';
            $html[] = $label;
            $html[] = '</label>';

            $html[] = JHtml::_('select.genericlist', $choices['default'], $name, '', '', '', $selected);

            $html[] = '</div>';
        }

        $html[] = '</div>';

        return implode("\n", $html);
    }
}
