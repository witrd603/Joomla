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

class TableFieldTranslation extends JTable
{
    var $id = null;
    var $field_id = null;
    var $lang_code = null;
    var $title = null;
    var $title_view = null;
    var $title_edit = null;
    var $title_search = null;
    var $description = null;
    var $values = null;

    function __construct(&$db)
    {
        parent::__construct('#__lovefactory_fields_translation', 'id', $db);
    }

    function find($options = array())
    {
        $dbo = JFactory::getDBO();
        $where = array();

        foreach ($options as $col => $val) {
            $where[] = $col . ' = ' . $dbo->Quote($val);
        }

        $query = ' SELECT t.*'
            . ' FROM #__lovefactory_fields_translation t'
            . ' WHERE ' . implode(' AND ', $where);
        $dbo->setQuery($query);
        $result = $dbo->loadObject();

        $this->bind($result);

        if (!$result) {
            $this->field_id = $options['field_id'];
            $this->lang_code = $options['lang_code'];
        }

        return $this;
    }
}
