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

class JFormFieldListExtra extends JFormFieldList
{
    protected $type = 'List';

    protected function getInput()
    {
        $value = $this->value;
        $options = $this->getOptions();
        $found = false;

        foreach ($options as $option) {
            if ($option->value == $this->value) {
                $found = true;
                $value = '';
                break;
            }
        }

        if (!$found) {
            $this->value = $this->element['extra'];
        }

        $js = array();

        $js[] = 'var elem = document.getElementById(\'' . $this->id . '_extra\')';
        $js[] = 'var extra = this.value != \'' . $this->element['extra'] . '\'';
        $js[] = 'elem.disabled = extra';
        $js[] = 'elem.style.display = extra ? \'none\' : \'block\'';
        $js[] = 'elem.value = \'\'';

        $this->element['onchange'] = implode(';', $js);

        $html = array();

        $html[] = parent::getInput();
        $html[] = '<input type="text" size="50" id="' . $this->id . '_extra" name="' . $this->name . '" value="' . $value . '" ' . ($found ? 'disabled="disabled"' : '') . ' style="display: ' . ($found ? 'none' : 'block') . '" />';

        return implode("\n", $html);
    }
}
