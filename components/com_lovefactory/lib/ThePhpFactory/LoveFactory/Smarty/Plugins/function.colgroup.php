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

function smarty_function_colgroup(array $params, Smarty_Internal_Template $template)
{
    $html = array();
    $cols = explode('|', $params['cols']);

    $html[] = '<colgroup>';

    foreach ($cols as $col) {
        $options = explode(',', $col);

        $style = $options[0] ? 'style="width: ' . $options[0] . 'px;"' : '';
        $class = isset($options[1]) ? 'class="' . $options[1] . '"' : '';

        $html[] = '<col ' . $style . ' ' . $class . ' />';
    }

    $html[] = '</colgroup>';

    return implode("\n", $html);
}
