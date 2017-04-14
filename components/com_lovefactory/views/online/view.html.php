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

class FrontendViewOnline extends FactoryView
{
//  protected
//    $get = array('items', 'pagination', 'rendererResults', 'pageResults', 'limitedResults', 'filter', 'filterDir'),
//    $behaviors = array('factoryTooltip', 'factoryjQueryUi', 'factoryAjaxAction'),
//    $css = array('views/results'),
//    $js = array('views/results')
//  ;

    protected
        $get = array('viewResults'),
        $css = array('icons'),
        $behaviors = array('factoryTooltip', 'factoryjQueryUi', 'factoryAjaxAction');
}
