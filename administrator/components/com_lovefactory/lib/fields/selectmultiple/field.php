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

class LoveFactoryFieldSelectMultiple extends LoveFactoryField
{
    protected $generatesDataColumn = true;
    protected $generatesVisibilityColumn = true;

    public function __construct($type, $field = null, $mode = 'view')
    {
        parent::__construct($type, $field, $mode);

        if ($minChoices = $this->params->get('min_choices', '')) {
            $this->addHelpText(FactoryText::sprintf('field_selectmulutile_min_choices', $minChoices), 'fa-exclamation-circle');
        }

        if ($maxChoices = $this->params->get('max_choices', '')) {
            $this->addHelpText(FactoryText::sprintf('field_selectmulutile_max_choices', $maxChoices), 'fa-exclamation-circle');
        }
    }

    public function renderInputEdit()
    {
        return LoveFactoryFieldMultipleChoiceInterface::renderEditSelectMultiple(
            $this->getChoices(),
            $this->getHtmlName(),
            $this->data,
            $this->getHtmlId(),
            array('mode' => $this->mode)
        );
    }

    public function renderInputView()
    {
        return LoveFactoryFieldMultipleChoiceInterface::renderView($this->getChoices(), $this->data, $this->params->get('display_mode', 'line'));
    }

    public function validate()
    {
        if (!parent::validate()) {
            return false;
        }

        $valid = LoveFactoryFieldMultipleChoiceInterface::validate($this->getLabel(), $this->data, $this->params->get('min_choices', 0), $this->params->get('max_choices', 0));

        if (true !== $valid) {
            $this->setError($valid);
            return false;
        }

        return true;
    }

    public function convertDataToProfile()
    {
        $this->data = LoveFactoryFieldMultipleChoiceInterface::convertDataToProfile($this->data);

        return parent::convertDataToProfile();
    }

    public function getQuerySearchCondition($query)
    {
        return LoveFactoryFieldMultipleChoiceInterface::getQuerySearchCondition($query, $this->getId(), $this->data, $this->params);
    }

    public function filterData()
    {
        $this->data = LoveFactoryFieldMultipleChoiceInterface::filterData($this->data, $this->getChoices());
    }

    public function getDisplayData()
    {
        return LoveFactoryFieldMultipleChoiceInterface::getDisplayData(
            $this->getChoices(),
            $this->data
        );
    }
}
