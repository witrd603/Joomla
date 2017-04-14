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
 * Smarty count_words modifier plugin
 *
 * Type:     modifier<br>
 * Name:     count_words<br>
 * Purpose:  count the number of words in a text
 *
 * @link http://www.smarty.net/manual/en/language.modifier.count.words.php count_words (Smarty online manual)
 * @author Uwe Tews
 * @param array $params parameters
 * @return string with compiled code
 */
function smarty_modifiercompiler_count_words($params, $compiler)
{
    if (Smarty::$_MBSTRING) {
        // return 'preg_match_all(\'#[\w\pL]+#' . Smarty::$_UTF8_MODIFIER . '\', ' . $params[0] . ', $tmp)';
        // expression taken from http://de.php.net/manual/en/function.str-word-count.php#85592
        return 'preg_match_all(\'/\p{L}[\p{L}\p{Mn}\p{Pd}\\\'\x{2019}]*/' . Smarty::$_UTF8_MODIFIER . '\', ' . $params[0] . ', $tmp)';
    }
    // no MBString fallback
    return 'str_word_count(' . $params[0] . ')';
}

?>
