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

class FrontendModelPage extends FactoryModel
{
    public function getZonesForPage($page)
    {
        static $zones = array();

        if (!isset($zones[$page])) {
            // Load the page
            $table = JTable::getInstance('Page', 'Table');
            $table->loadByName($page);

            // Check if fields are set
            if ('' == $table->fields) {
                die(JText::_('Improper page setup! At least one field must be selected!'));
            }

            $zones[$page] = explode('#', $table->fields);
        }

        return $zones[$page];
    }
}
