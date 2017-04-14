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

class FrontendModelTerms extends FactoryModel
{
    function getData()
    {
        $lang = JFactory::getLanguage();

        $query = ' SELECT f.*,'
            . '   IF (t.id IS NOT NULL AND t.values <> "", t.values, f.values) AS `values`'
            . ' FROM #__lovefactory_fields f'
            . ' LEFT JOIN #__lovefactory_fields_translation t ON (t.field_id = f.id AND t.lang_code = "' . $lang->getTag() . '")'
            . ' WHERE f.type_id = 28';
        $this->_db->setQuery($query);
        $result = $this->_db->loadObject();

        $field = $this->getTable('field');
        $field->bind($result);

        return nl2br($field->getParam('values'));
    }
}
