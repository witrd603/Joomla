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

function smarty_function_render(array $params, Smarty_Internal_Template $template)
{
    list($class, $method) = explode(':', $params['controller']);

    if (!class_exists($class)) {
        if (!preg_match('/.+Controller(.+)/', $class, $matches)) {
            throw new Exception(sprintf('Controller not found in class "%s"!', $class), 500);
        }

        JLoader::register($class, JPATH_SITE . '/components/com_lovefactory/controllers/' . strtolower($matches[1]) . '.php');
    }

    if (!class_exists($class)) {
        throw new Exception(sprintf('Controller not found "%s"!', $class), 500);
    }

    $controller = new $class;
    $array = array();

    $r = new ReflectionClass($controller);
    $parameters = $r->getMethod($method)->getParameters();

    foreach ($parameters as $param) {
        if (isset($params[$param->getName()])) {
            $array[] = $params[$param->getName()];
        }
    }

    return call_user_func_array(array($controller, $method), $array);
}
