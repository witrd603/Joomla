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

class LoveFactoryFieldBirthdate extends LoveFactoryField
{
    protected $generatesDataColumn = true;
    protected $generatesVisibilityColumn = true;

    public function renderInputEdit()
    {
        $html = array();

        $year = is_array($this->data) && isset($this->data['year']) ? $this->data['year'] : substr($this->data, 0, 4);
        $month = is_array($this->data) && isset($this->data['month']) ? $this->data['month'] : substr($this->data, 4, 2);
        $day = is_array($this->data) && isset($this->data['day']) ? $this->data['day'] : substr($this->data, 6, 2);

        $max_age = $this->params->get('max_age', 40);
        $min_age = $this->params->get('min_age', 18);

        // Day
        $options = array();
        for ($i = 1; $i < 32; $i++) {
            $options[] = JHTML::_('select.option', str_pad($i, 2, 0, STR_PAD_LEFT));
        }

        array_unshift($options, JHtml::_('select.option', '', ''));
        $html['day'] = JHTML::_('select.genericlist', $options, $this->getHtmlName() . '[day]', '', 'value', 'text', $day, $this->getHtmlId() . '_day');

        // Month
        $options = array();
        $date = JFactory::getDate();
        for ($i = 1; $i < 13; $i++) {
            if ('numeric' == $this->params->get('month_format', 'numeric')) {
                $text = str_pad($i, 2, 0, STR_PAD_LEFT);
            } else {
                $text = $date->monthToString($i);
            }

            $i = str_pad($i, 2, 0, STR_PAD_LEFT);

            $options[] = JHTML::_('select.option', $i, $text);
        }

        array_unshift($options, JHtml::_('select.option', '', ''));
        $html['month'] = JHTML::_('select.genericlist', $options, $this->getHtmlName() . '[month]', '', 'value', 'text', $month, $this->getHtmlId() . '_month');

        // Year
        $min_year = JFactory::getDate()->format('Y') - $max_age - 1;
        $max_year = JFactory::getDate()->format('Y') - $min_age;

        $options = array();
        for ($i = $max_year; $i >= $min_year; $i--) {
            $options[] = JHTML::_('select.option', $i);
        }

        array_unshift($options, JHtml::_('select.option', '', ''));
        $html['year'] = JHTML::_('select.genericlist', $options, $this->getHtmlName() . '[year]', '', 'value', 'text', $year, $this->getHtmlId() . '_year');

        // Output field.
        $spacer = '' . $this->getParam('separator', '') . '' . "\n";

        if ('dmY' == $this->params->get('format', 'dmY')) {
            $output = $html['day'] . $spacer . $html['month'] . $spacer . $html['year'];
        } else {
            $output = $html['month'] . $spacer . $html['day'] . $spacer . $html['year'];
        }

        return $output;
    }

    public function renderInputView()
    {
        $data = $this->parseValueFromProfile($this->data);

        if (false === $data) {
            return $this->renderInputBlank();
        }

        return FactoryText::sprintf('field_birthdate_years_old', $this->getYearsOld($data));
    }

    public function renderInputSearch()
    {
        $html = array();
        $max_age = $this->params->get('max_age', 40);
        $min_age = $this->params->get('min_age', 18);

        $options = array();
        for ($i = $min_age; $i <= $max_age; $i++) {
            $options[] = JHTML::_('select.option', $i);
        }
        array_unshift($options, JHtml::_('select.option', '', ''));

        $min = is_array($this->data) && isset($this->data['min']) ? $this->data['min'] : '';
        $max = is_array($this->data) && isset($this->data['max']) ? $this->data['max'] : '';

        $html['min'] = JHTML::_('select.genericlist', $options, $this->getHtmlName() . '[min]', '', 'value', 'text', $min, $this->getHtmlId() . '_min');
        $html['max'] = JHTML::_('select.genericlist', $options, $this->getHtmlName() . '[max]', '', 'value', 'text', $max, $this->getHtmlId() . '_max');

        return FactoryText::sprintf('field_birthdate_search_output', $html['min'], $html['max']);
    }

    public function validate()
    {
        if (!parent::validate()) {
            return false;
        }

        if (!checkdate($this->data['month'], $this->data['day'], $this->data['year'])) {
            $this->setError(FactoryText::sprintf('field_birthdate_invalid_date', $this->getLabel()));
            return false;
        }

        $years = $this->getYearsOld($this->data);
        $max_age = $this->params->get('max_age', 40);
        $min_age = $this->params->get('min_age', 18);

        if ($years > $max_age || $years < $min_age) {
            $this->setError(FactoryText::sprintf('field_birthdate_error_age', $min_age, $max_age));
            return false;
        }

        return true;
    }

    public function convertDataToProfile()
    {
        $this->data = $this->data['year'] . $this->data['month'] . $this->data['day'];

        return parent::convertDataToProfile();
    }

    public function getQuerySearchCondition($query)
    {
        if (!is_array($this->data) || !$this->data) {
            return false;
        }

        $output = array();

        if (isset($this->data['min']) && $this->data['min']) {
            $value = JFactory::getDate('-' . $this->data['min'] . 'years')->format('Ymd');
            $output[] = $query->quoteName('p.' . $this->getId()) . ' <= ' . $query->quote($value);
        }

        if (isset($this->data['max']) && $this->data['max']) {
            $value = JFactory::getDate('-' . ($this->data['max'] + 1) . 'years +1 day')->format('Ymd');
            $output[] = $query->quoteName('p.' . $this->getId()) . ' >= ' . $query->quote($value);
        }

        if (!$output) {
            return false;
        }

        $output = '(' . implode(') AND (', $output) . ')';

        return $output;
    }

    public function filterData()
    {
        if (!is_array($this->data)) {
            $this->data = null;

            return true;
        }

        if (!isset($this->data['day'])) {
            $this->data['day'] = 0;
        }

        if (!isset($this->data['month'])) {
            $this->data['month'] = 0;
        }

        if (!isset($this->data['year'])) {
            $this->data['year'] = 0;
        }

        JArrayHelper::toInteger($this->data);

        $this->data['day'] = str_pad($this->data['day'], 2, 0, STR_PAD_LEFT);
        $this->data['month'] = str_pad($this->data['month'], 2, 0, STR_PAD_LEFT);
    }

    protected function parseValueFromProfile($data)
    {
        $output = array();

        $output['year'] = substr($data, 0, 4);
        $output['month'] = substr($data, 4, 2);
        $output['day'] = substr($data, 6, 2);

        if (!$output['year'] || !$output['month'] || !$output['day']) {
            return false;
        }

        return $output;
    }

    protected function getYearsOld($data)
    {
        $years = JFactory::getDate()->format('Y') - $data['year'];
        $months = JFactory::getDate()->format('m') - $data['month'];
        $days = JFactory::getDate()->format('d') - $data['day'];

        if ($days < 0) {
            $months--;
        }

        if ($months < 0) {
            $years--;
        }

        return $years;
    }
}
