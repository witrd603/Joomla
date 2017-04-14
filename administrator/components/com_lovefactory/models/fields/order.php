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

jimport('joomla.form.formfield');

class JFormFieldOrder extends JFormField
{
    public $type = 'Order';

    protected function getInput()
    {
        // Initialize variables.
        $html = array();

        if ($this->element['readonly'] == 'true') {
            $html[] = '<div>';
            $html[] = '<label><a href="index.php?option=com_lovefactory&controller=order&task=edit&id=' . $this->value . '">' . $this->value . '</a></label>';
            $html[] = '</div>';
        }

        return implode("\n", $html);
    }
}
