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

JLoader::register('FrontendModelSearch', LoveFactoryApplication::getInstance()->getPath('component_site') . DS . 'models' . DS . 'search.php');

class FrontendModelAdvanced extends FrontendModelSearch
{
    public function getPage($page = 'search_advanced', $mode = 'search')
    {
        return parent::getPage($page, $mode);
    }
}
