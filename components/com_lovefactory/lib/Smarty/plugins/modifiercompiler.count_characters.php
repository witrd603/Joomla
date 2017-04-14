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
 * Smarty count_characters modifier plugin
 *
 * Type:     modifier<br>
 * Name:     count_characteres<br>
 * Purpose:  count the number of characters in a text
 *
 * @link http://www.smarty.net/manual/en/language.modifier.count.characters.php count_characters (Smarty online manual)
 * @author Uwe Tews
 * @param array $params parameters
 * @return string with compiled code
 */
function smarty_modifiercompiler_count_characters($params, $compiler)
{
    if (!isset($params[1]) || $params[1] != 'true') {
        return 'preg_match_all(\'/[^\s]/' . Smarty::$_UTF8_MODIFIER . '\',' . $params[0] . ', $tmp)';
    }
    if (Smarty::$_MBSTRING) {
        return 'mb_strlen(' . $params[0] . ', \'' . addslashes(Smarty::$_CHARSET) . '\')';
    }
    // no MBString fallback
    return 'strlen(' . $params[0] . ')';
}

?>
