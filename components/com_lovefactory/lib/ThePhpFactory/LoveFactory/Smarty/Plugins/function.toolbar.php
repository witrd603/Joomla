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

function smarty_function_toolbar(array $params, Smarty_Internal_Template $template)
{
    $active = isset($params['active']) ? $params['active'] : $template->getTemplateVars('viewName');
    $userId = isset($params['userId']) ? $params['userId'] : null;

    return JHtml::_('LoveFactory.toolbar', $params['toolbar'], $active, $userId);
}
