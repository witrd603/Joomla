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

function smarty_modifier_nl2p($string, $nl2br = true)
{
    $string = str_replace(array("\r\n", "\r"), "\n", $string);
    $parts = explode("\n\n", $string);
    $string = '';

    foreach ($parts as $part) {
        $part = trim($part);

        if ($part) {
            if ($nl2br) {
                $part = nl2br($part);
            }

            $string .= "<p>$part</p>\n\n";
        }
    }

    return $string;
}
