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

class LoveFactoryFieldHeight extends LoveFactoryFieldSlider
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

        $params['labelAfter'] = FactoryText::_('field_height_unit_metric_label');
        $params['unit'] = $this->getParam('unit', 'metric');
        $params['step'] = $this->getParam('interval.step', 10);

        return $params;
    }

    protected function getParamsSearchInterval()
    {
        $params = parent::getParamsSearchInterval();

        $params['labelAfter'] = FactoryText::_('field_height_unit_metric_label');
        $params['unit'] = $this->getParam('unit', 'metric');
        $params['step'] = $this->getParam('interval.step', 10);

        return $params;
    }

    protected function renderInputViewMetric()
    {
        if (is_null($this->data) || !$this->data) {
            return $this->renderInputBlank();
        }

        $html = array();

        $html[] = $this->data;
        $html[] = FactoryText::_('field_height_unit_metric_label');

        return implode("\n", $html);
    }

    protected function renderInputViewImperial()
    {
        if (is_null($this->data) || !$this->data) {
            return $this->renderInputBlank();
        }

        $data = $this->convertMetricToImperial($this->data);
        $html = array();

        $html[] = $data['feet'] . FactoryText::_('field_height_unit_imperial_ft_short_label');
        $html[] = $data['inches'] . FactoryText::_('field_height_unit_imperial_in_short_label');

        return implode("\n", $html);
    }

    protected function convertMetricToImperial($value)
    {
        $inches = intval($value) * .3937008;
        $feet = floor($inches / 12);
        $inches = $inches % 12;

        return array('feet' => $feet, 'inches' => $inches);
    }

    protected function renderInputCommon($params, $class = 'lovefactory-slider-height')
    {
        FactoryHtml::script('fields/height');

        return parent::renderInputCommon($params, $class);
    }
}
