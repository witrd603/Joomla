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

class LoveFactoryFieldSlider extends LoveFactoryField
{
    protected $generatesDataColumn = true;
    protected $generatesVisibilityColumn = true;

    public function renderInputEdit()
    {
        $type = $this->getParam('type', 'interval');

        if ('interval' == $type) {
            $params = $this->getParamsEditInterval();
        } else {
            $params = $this->getParamsEditValues();
        }

        return $this->renderInputCommon($params);
    }

    public function renderInputView()
    {
        if ('' == $this->data) {
            return $this->renderInputBlank();
        }

        $type = $this->getParam('type', 'interval');

        if ('interval' == $type) {
            return $this->data;
        }

        $choices = $this->getChoices();

        return $choices[$this->data];
    }

    public function renderInputSearch()
    {
        $type = $this->getParam('type', 'interval');

        if ('interval' == $type) {
            $params = $this->getParamsSearchInterval();
        } else {
            $params = $this->getParamsSearchValues();
        }

        return $this->renderInputCommon($params);
    }

    public function filterData()
    {
        if ('' === $this->data) {
            $this->data = null;
            return true;
        }

        $type = $this->getParam('type', 'interval');

        if ('interval' == $type) {
            $min = $this->getParam('interval.min', 0);
            $max = $this->getParam('interval.max', 0);
            $step = $this->getParam('interval.step', 0);

            if (intval($this->data) < $min || intval($this->data) > $max || intval($this->data) % $step) {
                $this->data = null;
                return true;
            }
        } else {
            if (intval($this->data) < 0 || intval($this->data) > count($this->getChoices()) - 1) {
                $this->data = null;
                return true;
            }
        }

        $this->data = intval($this->data);

        return true;
    }

    public function getQuerySearchCondition($query)
    {
        $array = array();

        if (isset($this->data[0])) {
            $array[] = $query->quoteName('p.' . $this->getId()) . ' >= ' . $query->quote(intval($this->data[0]));
        }

        if (isset($this->data[1])) {
            $array[] = $query->quoteName('p.' . $this->getId()) . ' <= ' . $query->quote(intval($this->data[1]));
        }

        if (!$array) {
            return false;
        }

        return '(' . implode(' AND ', $array) . ')';
    }

    protected function getParamsSearchInterval()
    {
        $values = array();
        $min = $this->getParam('interval.min', 0);
        $max = $this->getParam('interval.max', 0);
        $step = $this->getParam('interval.step', 0);

        $values[] = isset($this->data[0]) ? $this->data[0] : '';
        $values[] = isset($this->data[1]) ? $this->data[1] : '';

        $params = array(
            'min' => $min,
            'max' => $max,
            'step' => $step,
            'range' => true,
            'values' => array_values($values),
            'label' => $this->renderLabel(),
            'inputName' => $this->getHtmlName(),
            'labelBlank' => FactoryText::_('field_slider_label_blank'),
        );

        return $params;
    }

    protected function getParamsSearchValues()
    {
        $values = array();
        $choices = $this->getChoices();
        $min = 0;
        $max = count($choices) - 1;
        $step = 1;

        $values[] = isset($this->data[0]) ? $this->data[0] : '';
        $values[] = isset($this->data[1]) ? $this->data[1] : '';

        $params = array(
            'min' => $min,
            'max' => $max,
            'step' => $step,
            'values' => array_values($values),
            'range' => true,
            'label' => $this->renderLabel(),
            'inputName' => $this->getHtmlName(),
            'labelBlank' => FactoryText::_('field_slider_label_blank'),
            'choices' => $choices,
        );

        return $params;
    }

    protected function getParamsEditInterval()
    {
        $min = $this->getParam('interval.min', 0);
        $max = $this->getParam('interval.max', 0);
        $step = $this->getParam('interval.step', 0);
        $value = !is_null($this->data) && $this->data <= $max && $this->data >= $min ? $this->data : '';

        $params = array(
            'min' => $min,
            'max' => $max,
            'step' => $step,
            'value' => $value,
            'label' => '',
            'inputName' => $this->getHtmlName(),
            'labelBlank' => FactoryText::_('field_slider_label_blank'),
        );

        return $params;
    }

    protected function getParamsEditValues()
    {
        $choices = $this->getChoices();
        $min = 0;
        $max = count($choices) - 1;
        $step = 1;
        $value = !is_null($this->data) && $this->data <= $max && $this->data >= $min ? $this->data : '';

        $params = array(
            'min' => $min,
            'max' => $max,
            'step' => $step,
            'value' => $value,
            'label' => '',
            'inputName' => $this->getHtmlName(),
            'labelBlank' => FactoryText::_('field_slider_label_blank'),
            'choices' => $choices,
        );

        return $params;
    }

    protected function renderInputCommon($params, $class = 'lovefactory-slider')
    {
        JHtml::_('FactoryFramework.behavior', 'JQueryUI');
        FactoryHtml::script('jquery-ui.lovefactoryslider');
        FactoryHtml::script('fields/slider');
        FactoryHtml::stylesheet('fields/slider');

        $html = array();

        $html[] = '<div class="' . $class . '" data-slider=\'' . json_encode($params) . '\' ></div>';

        return implode("\n", $html);
    }
}
