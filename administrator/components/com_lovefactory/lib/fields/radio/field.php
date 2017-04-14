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

class LoveFactoryFieldRadio extends LoveFactoryField
{
    protected $generatesDataColumn = true;
    protected $generatesVisibilityColumn = true;

    public function renderInputEdit()
    {
        return LoveFactoryFieldSingleChoiceInterface::renderEditRadio(
            $this->getChoices(),
            $this->getHtmlName(),
            $this->data,
            $this->getHtmlId(),
            array(
                'mode' => $this->params->get('display_mode', 'row'),
                'id' => $this->getFieldId(),
                'images' => $this->getParam('images'),
            )
        );
    }

    public function renderInputView()
    {
        return LoveFactoryFieldSingleChoiceInterface::renderView(
            $this->getChoices(),
            $this->data,
            array(
                'id' => $this->getFieldId(),
                'images' => $this->getParam('images'),
            )
        );
    }

    public function getData()
    {
        return LoveFactoryFieldSingleChoiceInterface::getData($this->getChoices(), $this->data);
    }

    public function getQuerySearchCondition($query)
    {
        return LoveFactoryFieldSingleChoiceInterface::getQuerySearchCondition($query, $this->getId(), $this->data);
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
