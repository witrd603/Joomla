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

function smarty_function_JRoute(array $params, Smarty_Internal_Template $template)
{
    if (isset($params['view'])) {
        return FactoryRoute::view($params['view']);
    }

    if (isset($params['task'])) {
        list($controller, $task) = explode('.', $params['task']);
        return FactoryRoute::_('controller=' . $controller . '&task=' . $task);
    }

    if (isset($params['_'])) {
        return FactoryRoute::_($params['_']);
    }

    if (isset($params['raw'])) {
        return JRoute::_($params['raw']);
    }

    return '';
}
