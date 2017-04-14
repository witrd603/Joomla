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

class FrontendModelFields extends FactoryModel
{
    var $_fields;
    var $_page_id;

    function getFieldsForPage($page, $user_id = null)
    {
        if (isset($this->_fields)) {
            return false;
        }

        $pages = !is_array($page) ? array($page) : $page;
        $ids = array();

        foreach ($pages as $page) {
            // Get the zones
            $modelPage = JModelLegacy::getInstance('Page', 'FrontendModel');
            $zones = $modelPage->getZonesForPage($page);

            foreach ($zones as $zone) {
                $zone = explode('_', $zone);

                $ids[] = $zone[2];
            }
        }

        $ids = array_unique($ids);

        // Get the fields
        $this->_fields = $this->find($ids, $pages[0], $user_id);
    }

    function find($ids, $page, $user_id = null)
    {
        $special = array('edit');

        if (in_array($page, $special)) {
            $user = JFactory::getUser();
            $user_id = null == $user_id ? $user->id : $user_id;

            $query = ' SELECT f.*'
                . ' FROM #__lovefactory_fields f'
                . ' WHERE f.id IN (' . implode(', ', $ids) . ')';
        } else {
            $query = ' SELECT f.*'
                . ' FROM #__lovefactory_fields f'
                . ' WHERE f.id IN (' . implode(', ', $ids) . ')';
        }

        $language = JFactory::getLanguage();

        $query = ' SELECT f.*,'
            . '   IF (t.id IS NOT NULL AND t.title <> "", t.title, f.title) AS title,'
            . '   IF (t.id IS NOT NULL AND t.title_view <> "", t.title_view, f.title_view) AS title_view,'
            . '   IF (t.id IS NOT NULL AND t.title_edit <> "", t.title_edit, f.title_edit) AS title_edit,'
            . '   IF (t.id IS NOT NULL AND t.title_search <> "", t.title_search, f.title_search) AS title_search,'
            . '   IF (t.id IS NOT NULL AND t.description <> "", t.description, f.description) AS description,'
            . '   IF (t.id IS NOT NULL AND t.values <> "", t.values, f.values) AS `values`'
            . ' FROM #__lovefactory_fields f'
            . ' LEFT JOIN #__lovefactory_fields_translation t ON (t.field_id = f.id AND t.lang_code = "' . $language->getTag() . '")'
            . ' WHERE f.id IN (' . implode(', ', $ids) . ')';

        $query .= ' AND f.published = 1';

        $this->_db->setQuery($query);
        $fields = $this->_db->loadObjectlist();

        $array = array();
        foreach ($fields as $field) {
            $table = $this->getTable('field', 'Table');
            $table->bind($field);

//      if (in_array($page, $special))
//      {
//        $table->set_user_visibility = $field->tester;
//      }

            $array[$field->id] = $table;
        }

        return $array;
    }

    function addRequiredConditionsFor($fields)
    {
        $content = array();

        $content[] = 'var required_message = "' . JText::_('THIS_FILES_IS_REQUIRED') . '";';
        $content[] = 'var conditions = new Array();';
        foreach ($fields as $i => $field) {
            $content[] = 'conditions[' . $i . '] = { id: ' . $field['id'] . ', type_id: ' . $field['type_id'] . '};';
        }

        $document = JFactory::getDocument();
        $document->addScriptDeclaration(implode("\n", $content));
    }

    function getFieldsIdsForJoomlaRegistration($fields = array())
    {
        $query = ' SELECT f.id, f.type_id'
            . ' FROM #__lovefactory_fields f'
            . ' WHERE f.type_id IN (12, 18, 19, 20)';
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList('type_id');
    }

    function getPhotoRequiredOnRegistration()
    {
        $query = ' SELECT f.required'
            . ' FROM #__lovefactory_fields f'
            . ' WHERE f.type_id = 21'
            . ' AND f.published = 1';
        $this->_db->setQuery($query);

        return $this->_db->loadResult();
    }
}
