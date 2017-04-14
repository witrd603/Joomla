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

function smarty_function_loadtemplate(array $params, Smarty_Internal_Template $template)
{
    if (false !== strpos($params['_'], '/')) {
        list ($view, $tpl) = explode('/', $params['_']);
        $path = LoveFactoryApplication::getInstance()->getPath('component_site') . DS . 'views' . DS . $view . DS . 'tmpl' . DS;
    } else {
        $tpl = $params['_'];
        $path = '';
    }

    foreach ($params as $key => $value) {
        if ('_' == $key) {
            continue;
        }

        $template->smarty->assignByRef($key, $value);
    }

    $content = $template->smarty->fetch($path . $tpl . '.tpl');

    return $content;
}
