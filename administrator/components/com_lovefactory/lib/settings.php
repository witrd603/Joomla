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

class JHtmlSettings
{
    public static function boolean($id, $title, $selected, $tip = null, $values = array(), $warning = null, $related = null)
    {
        $options = array(
            0 => isset($values[0]) ? JText::_($values[0]) : JText::_('JNO'),
            1 => isset($values[1]) ? JText::_($values[1]) : JText::_('JYES'),
        );

        $select = JHtml::_('select.genericlist', $options, $id, null, 'value', 'text', $selected);
        $hasTip = is_null($tip) ? '' : 'hasTooltip';
        $titleTip = is_null($tip) ? '' : JText::_($tip);

        $warning = is_null($warning) ? '' : '<br style="clear: left;" /><span class="lovefactory-button lovefactory-bullet-error lovefactory-error-field">' . JText::_($warning) . '</span>';
        $related = is_null($related) ? '' : $related . '_related';

        $output = '<tr class="' . $hasTip . ' ' . $related . '" title="' . $titleTip . '">'
            . '  <td width="40%" class="paramlist_key">'
            . '    <span class="editlinktip">'
            . '      <label for="' . $id . '">' . JText::_($title) . '</label>'
            . '    </span>'
            . '  </td>'
            . '  <td class="paramlist_value">'
            . $select
            . $warning
            . '  </td>'
            . '</tr>';

        return $output;
    }
}
