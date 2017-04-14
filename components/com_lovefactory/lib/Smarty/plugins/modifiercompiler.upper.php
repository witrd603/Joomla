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

/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifierCompiler
 */

/**
 * Smarty upper modifier plugin
 *
 * Type:     modifier<br>
 * Name:     lower<br>
 * Purpose:  convert string to uppercase
 *
 * @link http://smarty.php.net/manual/en/language.modifier.upper.php lower (Smarty online manual)
 * @author Uwe Tews
 * @param array $params parameters
 * @return string with compiled code
 */
function smarty_modifiercompiler_upper($params, $compiler)
{
    if (Smarty::$_MBSTRING) {
        return 'mb_strtoupper(' . $params[0] . ', \'' . addslashes(Smarty::$_CHARSET) . '\')';
    }
    // no MBString fallback
    return 'strtoupper(' . $params[0] . ')';
}

?>
