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

JLoader::register('LoveFactoryFieldSlider', JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/fields/slider/field.php');

class LoveFactoryFieldWeight extends LoveFactoryFieldSlider
{
    public function renderInputView()
    {
        if ('metric' == $this->getParam('unit', 'metric')) {
            return $this->renderInputViewMetric();
        }

        return $this->renderInputViewImperial();
    }

    protected function getParamsEditInterval()
    {
        $params = parent::getParamsEditInterval();
        $unit = $this->getParam('unit', 'metric');
        $step = $this->getParam('interval.step', 10);

        $params['labelAfter'] = FactoryText::_('field_weight_unit_' . $unit . '_label');
        $params['unit'] = $unit;
        $params['step'] = $step;

        return $params;
    }

    protected function getParamsSearchInterval()
    {
        $params = parent::getParamsSearchInterval();
        $unit = $this->getParam('unit', 'metric');
        $step = $this->getParam('interval.step', 10);

        $params['labelAfter'] = FactoryText::_('field_weight_unit_' . $unit . '_label');
        $params['unit'] = $unit;
        $params['step'] = $step;

        return $params;
    }

    protected function renderInputViewMetric()
    {
        if (is_null($this->data) || !$this->data) {
            return $this->renderInputBlank();
        }

        $html = array();

        $html[] = $this->data;
        $html[] = FactoryText::_('field_weight_unit_metric_label');

        return implode("\n", $html);
    }

    protected function renderInputViewImperial()
    {
        if (is_null($this->data) || !$this->data) {
            return $this->renderInputBlank();
        }

        $data = $this->convertMetricToImperial($this->data);
        $html = array();

        $html[] = $data;
        $html[] = FactoryText::_('field_weight_unit_imperial_label');

        return implode("\n", $html);
    }

    protected function convertMetricToImperial($value)
    {
        return floor($value * 2.2);
    }

    protected function renderInputCommon($params, $class = 'lovefactory-slider-weight')
    {
        FactoryHtml::script('fields/weight');

        return parent::renderInputCommon($params, $class);
    }
}
