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

function smarty_function_text(array $params, Smarty_Internal_Template $template)
{
    if (isset($params['text'])) {
        return FactoryText::_($params['text']);
    }

    if (isset($params['sprintf'])) {
        return call_user_func_array(array('FactoryText', 'sprintf'), $params);
    }

    if (isset($params['plural'])) {
        return call_user_func_array(array('FactoryText', 'plural'), $params);
    }
}
