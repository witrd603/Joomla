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

class BackendModelImport extends JModelLegacy
{
    public function getAdaptor()
    {
        JLoader::register('LoveFactoryImport', JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/imports/import.php');

        $adaptor = JFactory::getApplication()->input->getCmd('adaptor');

        return LoveFactoryImport::getInstance($adaptor);
    }
}
