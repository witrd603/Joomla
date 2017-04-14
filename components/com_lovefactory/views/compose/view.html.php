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

class FrontendViewCompose extends FactoryView
{
    protected $form;
    protected
        $get = array(
        'form',
        'data',
    ),
        $behaviors = array('factoryJQueryUI'),
        $routes = array('searchUser/task/message.searchuser'),
        $js = array('jquery.tokeninput'),
        $css = array('token-input'),
        $jtexts = array(
        'COM_LOVEFACTORY_COMPOSE_AUTOCOMPLETE_HINT',
        'COM_LOVEFACTORY_COMPOSE_AUTOCOMPLETE_SEARCHING',
        'COM_LOVEFACTORY_COMPOSE_AUTOCOMPLETE_NO_RESULTS',
    ),
        $javascriptVariables = array('receiver');
}
