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

class LoveFactoryFieldSelect extends LoveFactoryField
{
    protected $generatesDataColumn = true;
    protected $generatesVisibilityColumn = true;

    public function renderInputEdit()
    {
        return LoveFactoryFieldSingleChoiceInterface::renderEditSelect(
            $this->getChoices(),
            $this->getHtmlName(),
            $this->data,
            $this->getHtmlId(),
            $this->params->get('blank_choice', 1)
        );
    }

    public function renderInputSearch()
    {
        $mode = $this->getParam('search_mode_multiple', 0);

        if (!$mode) {
            return $this->renderInputEdit();
        }

        return LoveFactoryFieldMultipleChoiceInterface::renderEditSelectMultiple(
            $this->getChoices(),
            $this->getHtmlName(),
            $this->data,
            $this->getHtmlId());
    }

    public function renderInputView()
    {
        return LoveFactoryFieldSingleChoiceInterface::renderView($this->getChoices(), $this->data);
    }

    public function getData()
    {
        return LoveFactoryFieldSingleChoiceInterface::getData($this->getChoices(), $this->data);
    }

    public function getQuerySearchCondition($query)
    {
        $mode = $this->getParam('search_mode_multiple', 0);

        if (!$mode) {
            return LoveFactoryFieldSingleChoiceInterface::getQuerySearchCondition($query, $this->getId(), $this->data);
        }

        return LoveFactoryFieldMultipleChoiceInterface::getQuerySearchCondition($query, $this->getId(), $this->data, $this->params);
    }

    public function filterData()
    {
        $this->data = LoveFactoryFieldSingleChoiceInterface::filterData($this->data, $this->getChoices());
    }

    public function getDisplayData()
    {
        return LoveFactoryFieldMultipleChoiceInterface::getDisplayData(
            $this->getChoices(),
            $this->data
        );
    }
}
