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

defined('JPATH_BASE') or die;

class JFormFieldLoveFactoryPageSetup extends JFormField
{
    public $type = 'LoveFactoryPageSetup';
    protected $columns = array();

    public function __construct($form = null)
    {
        parent::__construct($form);

        for ($i = 1; $i <= 12; $i++) {
            $this->columns[] = array('value' => $i, 'text' => $i);
        }
    }

    protected function getInput()
    {
        $registry = new JRegistry($this->value);
        $value = $registry->toArray();

        $fields = $this->getFields($value);
        $pageType = $this->form->getValue('type');

        $html = array();

        $html[] = '<div id="page-setup"';
        $html[] = '  data-zone-header="' . htmlentities($this->getZoneHeader()) . '"';
        $html[] = '  data-zone-body="' . htmlentities($this->getZoneBody()) . '"';
        $html[] = '  data-column-header="' . htmlentities($this->getColumnHeader()) . '"';
        $html[] = '  data-column-body="' . htmlentities($this->getColumnBody()) . '"';
        $html[] = '  data-name="' . htmlentities($this->name) . '"';
        $html[] = '  data-page-type="' . htmlentities($pageType) . '"';
        $html[] = '>';

        $html[] = '<div class="main-buttons">';
        $html[] = '<a href="#" class="button button-add-zone">' . FactoryText::_('field_page_setup_add_zone') . '</a>';
        $html[] = '</div>';

        $html[] = '<ul class="zones">';

        foreach ($value as $zoneId => $zone) {
            $html[] = '<li class="zone">';

            $html[] = $this->getZoneHeader();
            $html[] = $this->getZoneBodyStart($zone['titles']);

            if (isset($zone['setup']) && $zone['setup']) {
                foreach ($zone['setup'] as $columnId => $column) {
                    $html[] = '<li class="column">';

                    $html[] = $this->getColumnHeader();
                    $html[] = $this->getColumnBodyStart();

                    if ($column) {
                        foreach ($column as $fieldId => $field) {
                            if (!isset($fields[$field])) {
                                continue;
                            }

                            $html[] = '<li class="field" id="' . $field . '">';
                            $html[] = '<a href="index.php?option=com_lovefactory&controller=field&task=edit&id=' . $field . '">' . $fields[$field]->title . '</a>';
                            $html[] = '<i class="factory-icon icon-arrow-move field-handler"></i>';
                            $html[] = '<i class="factory-icon icon-minus-circle button-field-remove"></i>';
                            $html[] = '</li>';
                        }
                    }

                    $columns = isset($zone['columns'][$columnId]) ? $zone['columns'][$columnId] : 1;
                    $html[] = $this->getColumnBodyEnd($columns);

                    $html[] = '</li>';
                }
            }

            $html[] = $this->getZoneBodyEnd();

            $html[] = '</li>';
        }

        $html[] = '</ul>';

        $html[] = '</div>';

        return implode("\n", $html);
    }

    protected function getZoneHeader()
    {
        $html = array();

        $html[] = '<div class="zone-header">';
        $html[] = '<a href="#" class="button button-add-column">' . FactoryText::_('field_page_setup_add_column') . '</a>';
        $html[] = '<a href="#" class="button button-remove-zone">' . FactoryText::_('field_page_setup_remove_zone') . '</a>';
        $html[] = '<i class="factory-icon icon-arrow-resize-090 zone-handler"></i>';
        $html[] = '</div>';

        return implode("\n", $html);
    }

    protected function getZoneBody()
    {
        $html = array();

        $html[] = $this->getZoneBodyStart();
        $html[] = $this->getZoneBodyEnd();

        return implode("\n", $html);
    }

    protected function getZoneBodyStart($titles = array())
    {
        $html = array();
        $titles = new JRegistry($titles);
        $languages = JLanguageHelper::getLanguages();

        $html[] = '<div class="zone-body">';

        $html[] = '<fieldset><legend>' . FactoryText::_('field_page_setup_fieldset_titles') . '</legend>';
        $html[] = '<ul class="titles">';
        $html[] = '<li><label for="">' . FactoryText::_('field_page_setup_title_default') . '</label><input name="default" value="' . $titles->get('default', '') . '" /></li>';

        foreach ($languages as $language) {
            $html[] = '<li><label>' . $language->title . '</label><input name="' . $language->lang_code . '" value="' . $titles->get($language->lang_code, '') . '" /></li>';
        }

        $html[] = '</ul>';

        $html[] = '</fieldset>';
        $html[] = '<fieldset class="fieldset-columns"><legend>' . FactoryText::_('field_page_setup_fieldset_columns') . '</legend>';

        $html[] = '<div class="alert alert-error columns-error" style="display: none;">';
        $html[] = 'Total number of Bootstrap grid columns must not be greater than 12!';
        $html[] = '</div>';

        $html[] = '<ul class="columns">';

        return implode("\n", $html);
    }

    protected function getZoneBodyEnd()
    {
        $html = array();

        $html[] = '</ul>';

        $html[] = '</fieldset>';

        $html[] = '</div>';

        return implode("\n", $html);
    }

    protected function getColumnHeader()
    {
        $html = array();

        $html[] = '<div class="column-header">';
        $html[] = '<a href="#" class="button button-add-field">' . FactoryText::_('field_page_setup_add_field') . '</a>';
        $html[] = '<a href="#" class="button button-remove-column">' . FactoryText::_('field_page_setup_remove_column') . '</a>';
        $html[] = '<i class="factory-icon icon-arrow-resize column-handler"></i>';
        $html[] = '</div>';

        return implode("\n", $html);
    }

    protected function getColumnBody()
    {
        $html = array();

        $html[] = $this->getColumnBodyStart();
        $html[] = $this->getColumnBodyEnd();

        return implode("\n", $html);
    }

    protected function getColumnBodyStart()
    {
        $html = array();

        $html[] = '<div class="column-body">';
        $html[] = '<ul class="fields">';

        return implode("\n", $html);
    }

    protected function getColumnBodyEnd($columns = 12)
    {
        $html = array();

        $html[] = '</ul>';
        $html[] = '</div>';

        $html[] = '<div style="padding: 10px; border-top: 1px solid #aaaaaa; background-color: #cccccc;">';
        $html[] = '<small>Bootstrap columns:</small> ' . JHtml::_('select.genericlist', $this->columns, 'columns', 'style="width: auto; vertical-align: baseline; margin: 0;"', 'value', 'text', $columns) . ' / 12';
        $html[] = '</div>';

        return implode("\n", $html);
    }

    protected function getFields($value)
    {
        $array = array();

        foreach ($value as $zone) {
            if (!isset($zone['setup']) || !$zone['setup']) {
                continue;
            }

            foreach ($zone['setup'] as $column) {
                if (!$column) {
                    continue;
                }

                foreach ($column as $field) {
                    $array[] = $field;
                }
            }
        }

        if (!$array) {
            return array();
        }

        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('f.id, f.title')
            ->from('#__lovefactory_fields f')
            ->where('f.id IN (' . implode(',', $array) . ')');
        $results = $dbo->setQuery($query)
            ->loadObjectList('id');

        return $results;
    }
}
