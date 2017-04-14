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
 * Smarty Resource Plugin
 *
 * @package Smarty
 * @subpackage TemplateResources
 * @author Rodney Rehm
 */

/**
 * Smarty Resource Plugin
 *
 * Base implementation for resource plugins that don't compile cache
 *
 * @package Smarty
 * @subpackage TemplateResources
 */
abstract class Smarty_Resource_Recompiled extends Smarty_Resource
{

    /**
     * populate Compiled Object with compiled filepath
     *
     * @param Smarty_Template_Compiled $compiled compiled object
     * @param Smarty_Internal_Template $_template template object
     * @return void
     */
    public function populateCompiledFilepath(Smarty_Template_Compiled $compiled, Smarty_Internal_Template $_template)
    {
        $compiled->filepath = false;
        $compiled->timestamp = false;
        $compiled->exists = false;
    }

}

?>
