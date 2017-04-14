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

class FrontendModelZones extends FactoryModel
{
    var $_zones;
    var $_titles;
    var $_page;

    function getZonesForPage($page)
    {
        $pages = JModelLegacy::getInstance('pages', 'FrontendModel');
        $page_id = $pages->_pages[$page];

        $page = $this->getTable('page');
        $page->loadPageTypeId($page_id);

        if ('' == $page->fields) {
            die(JText::_('Improper page setup! At least one field must be selected!'));
        }

        $this->_zones = explode('#', $page->fields);
        $this->_page = array(
            'description' => $page->description,
            'title' => $page->title
        );

        $this->_titles = array();
        $titles = explode('###', $page->titles);

        foreach ($titles as $title) {
            $title = explode('___', $title);

            if (isset($title[1])) {
                $this->_titles[$title[0]] = $title[1];
            }
        }
    }

    function getZonesForDisplayForPage($page, $user_id = null)
    {
        $this->getZonesForPage($page);

        $fields = JModelLegacy::getInstance('fields', 'FrontendModel');
        $fields->getFieldsForPage($page, $user_id);

        $formatting = array();
        $required = array();
        $types = array();

        foreach ($this->_zones as $zone) {
            $zone = explode('_', $zone);

            $row = $zone[0];
            $column = $zone[1];
            $field_id = $zone[2];

            if (!isset($fields->_fields[$field_id])) {
                continue;
            }

            if (!isset($formatting[$row])) {
                $formatting[$row] = array(
                    'title' => isset($this->_titles[$row]) ? $this->_titles[$row] : '',
                    'columns' => array()
                );
            }

            if (!isset($formatting[$row]['columns'][$column])) {
                $formatting[$row]['columns'][$column] = array();
            }

            $formatting[$row]['columns'][$column][] = $fields->_fields[$field_id];

            $types[] = $fields->_fields[$field_id]->type_id;

            if ($fields->_fields[$field_id]->required) {
                switch ($fields->_fields[$field_id]->type_id) {
                    case 10: // Sex
                        $type = 0 == $fields->_fields[$field_id]->getParam('mode') ? 3 : 6;
                        break;

                    case 11: // Looking for
                        switch ($fields->_fields[$field_id]->getParam('mode')) {
                            default:
                            case 1:
                                $type = 3;
                                break;

                            case 2:
                                $type = 6;
                                break;

                            case 3:
                                $type = 4;
                                break;

                            case 4:
                                $type = 5;
                                break;
                        }
                        break;

                    default:
                        $type = $fields->_fields[$field_id]->type_id;
                        break;
                }

                $required[] = array(
                    'id' => $fields->_fields[$field_id]->id,
                    'type_id' => $type,
                );
            }
        }

        return array(
            'formatting' => $formatting,
            'types' => $types,
            'required' => $required,
            'page' => $this->_page);
    }
}
