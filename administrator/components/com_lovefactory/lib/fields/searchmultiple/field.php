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

class LoveFactoryFieldSearchMultiple extends LoveFactoryField
{
    protected $accessPageWhiteList = array('search_quick', 'search_advanced', 'radius_search');

    public function renderInputSearch()
    {
        $data = htmlentities($this->data, ENT_COMPAT, 'UTF-8');
        $html = array();
        $display = 'inline';

        if (!is_null($this->data)) {
            $html[] = '<span id="' . $this->getHtmlId() . '_wrapper">' . $data . '&nbsp;<a href="#" onclick="document.getElementById(\'' . $this->getHtmlId() . '\').style.display = \'inline\'; document.getElementById(\'' . $this->getHtmlId() . '_wrapper\').style.display = \'none\'; return false;">(x)</a></span>';
            $display = 'none';
        }

        $html[] = '<input type="text" id="' . $this->getHtmlId() . '" name="' . $this->getHtmlName() . '" value="' . $data . '" style="display: ' . $display . ';" />';

        return implode("\n", $html);
    }

    public function getQuerySearchCondition($query)
    {
        $searchableFields = $this->params->get('searchable_fields', array());

        if (is_null($this->data) || '' == $this->data || !is_array($searchableFields) || !$searchableFields) {
            return false;
        }

        $conditions = array();
        $keywords = explode(',', $this->data);

        $operandKeywords = ' AND ';
        $operandFields = ' OR ';

        $dbo = JFactory::getDbo();
        $sql = $dbo->getQuery(true)
            ->select('f.*')
            ->from('#__lovefactory_fields f')
            ->where('f.id IN (' . implode(',', $searchableFields) . ')');
        $results = $dbo->setQuery($sql)
            ->loadAssocList();

        if (!$results) {
            return false;
        }

        foreach ($keywords as $keyword) {
            $keyword = trim($keyword);

            if ('' == $keyword) {
                continue;
            }

            $temp = array();
            foreach ($results as $field) {
                $table = JTable::getInstance('Field', 'Table');
                $table->bind($field);

                $field = LoveFactoryField::getInstance($table->type, $table, $this->mode);
                $field->bindValue($keyword);

                $condition = $field->getQuerySearchCondition($query);

                if (false !== $condition) {
                    $temp[] = $condition;
                }
            }

            if ($temp) {
                $conditions[] = '(' . implode($operandFields, $temp) . ')';
            }
        }

        if ($conditions) {
            $output = '(' . implode($operandKeywords, $conditions) . ')';
        } else {
            $output = false;
        }

        return $output;
    }
}
