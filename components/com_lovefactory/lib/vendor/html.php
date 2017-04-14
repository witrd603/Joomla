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

if (class_exists('JHtmlFactory')) {
    return false;
}

class JHtmlFactory
{
    public static function jQueryScript()
    {
        $option = 'com_lovefactory';

        $document = JFactory::getDocument();
        $filenames = func_get_args();

        if (!count($filenames) || 'jquery' != $filenames[0]) {
            array_unshift($filenames, 'jquery');
        }

        foreach ($filenames as $i => $filename) {
            $filename = ($i ? 'jquery-' : '') . strtolower($filename) . '-factory.js';

            // 1. Check if the file has already been added
            foreach ($document->_scripts as $script => $params) {
                if (false !== strpos($script, $filename)) {
                    // File was already added, continue
                    continue;
                }
            }

            $path = JURI::root(true) . '/components/' . $option . '/assets/js/jquery-factory/';

            // Add the script file
            $document->addScript($path . $filename);
        }

        return true;
    }

    function IEStylesheet($stylesheet)
    {
        $stylelink = '<!--[if lte IE 8]>'
            . '<link rel="stylesheet" type="text/css" href="' . JURI::root() . 'components/com_lovefactory/assets/css/' . $stylesheet . '" />'
            . '<![endif]-->';

        $document = JFactory::getDocument();
        $document->addCustomTag($stylelink);
    }
}
