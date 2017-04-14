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

class JFormFieldCountableRestriction extends JFormField
{
    protected function getInput()
    {
        $html = array();

        $checked = -1 == $this->value ? 'checked="checked"' : '';

        $html[] = '<div class="field-countable-restriction">';
        $html[] = '<label><input type="checkbox" ' . $checked . ' />Unlimited</label>';
        $html[] = '<input type="text" name="' . $this->name . '" value="' . $this->value . '" />';
        $html[] = '</div>';

        return implode($html);
    }
}
