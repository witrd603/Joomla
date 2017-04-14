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

jimport('joomla.application.component.model');

class FrontendModelPages extends FactoryModel
{
    var $_pages;

    function __construct()
    {
        parent::__construct();

        $this->_pages = array(
            'signup' => 1,
            'edit' => 2,
            'quicksearch' => 3,
            'advancedsearch' => 4,
            'result' => 5,
            'view' => 6,
            'friends' => 7,
            'fillin' => 8,
            'moreinfo' => 9,
        );
    }
}
