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

function smarty_block_form($params, $content, Smarty_Internal_Template $template, &$repeat)
{
    if ($repeat) {
        return;
    }

    $html = array();

    $method = isset($params['method']) ? $params['method'] : 'GET';
    $name = isset($params['name']) ? $params['name'] : 'adminForm';

    $html[] = JHtml::_('LoveFactory.beginForm', $params['url'], $method, $name);
    $html[] = $content;
    $html[] = '</form>';

    return implode("\n", $html);
}
