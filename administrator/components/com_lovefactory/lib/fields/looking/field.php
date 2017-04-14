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

class LoveFactoryFieldLooking extends LoveFactoryField
{
    protected $generatesDataColumn = true;
    protected $generatesVisibilityColumn = true;

    public function __construct($type, $field, $name)
    {
        parent::__construct($type, $field, $name);

        $table = JTable::getInstance('Field', 'Table');
        $table->load(array('type' => 'Gender'));
        $params = new JRegistry($table->params);
        $this->params->set('choices', $params->get('choices'));
    }

    public function renderInputEdit()
    {
        $type = $this->params->get('type', 'Select');

        switch ($type) {
            default:
            case 'Select':
                return LoveFactoryFieldSingleChoiceInterface::renderEditSelect($this->getChoices(), $this->getHtmlName(), $this->data, $this->getHtmlId(), $this->params->get('blank_choice', 1));
                break;

            case 'Radio':
                return LoveFactoryFieldSingleChoiceInterface::renderEditRadio($this->getChoices(), $this->getHtmlName(), $this->data, $this->getHtmlId());
                break;

            case 'SelectMultiple':
                return LoveFactoryFieldMultipleChoiceInterface::renderEditSelectMultiple(
                    $this->getChoices(),
                    $this->getHtmlName(),
                    $this->data,
                    $this->getHtmlId(),
                    array(
                        'min_choices' => $this->params->get('min_choices', ''),
                        'max_choices' => $this->params->get('max_choices', ''),
                        'mode' => $this->mode,
                    ));
                break;

            case 'Checkbox':
                return LoveFactoryFieldMultipleChoiceInterface::renderEditCheckbox(
                    $this->getChoices(),
                    $this->getHtmlName(),
                    $this->data,
                    $this->getHtmlId(),
                    array(),
                    array(
                        'min_choices' => $this->params->get('min_choices', ''),
                        'max_choices' => $this->params->get('max_choices', ''),
                        'mode' => $this->params->get('display_mode', 'row'),
                    ));
                break;
        }
    }

    public function renderInputView()
    {
        $type = $this->params->get('type', 'Select');

        switch ($type) {
            default:
            case 'Select':
            case 'Radio':
                return LoveFactoryFieldSingleChoiceInterface::renderView($this->getChoices(), $this->data);
                break;

            case 'SelectMultiple':
            case 'Checkbox':
                return LoveFactoryFieldMultipleChoiceInterface::renderView($this->getChoices(), $this->data, $this->params->get('display_mode', 'line'));
                break;
        }
    }

    public function getId()
    {
        return 'looking';
    }

    public function validate()
    {
        if (!parent::validate()) {
            return false;
        }

        $type = $this->params->get('type', 'Select');

        switch ($type) {
            case 'SelectMultiple':
            case 'Checkbox':
                $valid = LoveFactoryFieldMultipleChoiceInterface::validate($this->getLabel(), $this->data, $this->params->get('min_choices', 0), $this->params->get('max_choices', 0));

                if (true !== $valid) {
                    $this->setError($valid);
                    return false;
                }
                break;
        }

        return true;
    }

    public function getQuerySearchCondition($query)
    {
        $type = $this->params->get('type', 'Select');

        switch ($type) {
            default:
            case 'Select':
            case 'Radio':
                $output = LoveFactoryFieldSingleChoiceInterface::getQuerySearchCondition($query, $this->getId(), $this->data);
                break;

            case 'SelectMultiple':
            case 'Checkbox':
                $output = LoveFactoryFieldMultipleChoiceInterface::getQuerySearchCondition($query, $this->getId(), $this->data, $this->params);
                break;
        }

        return $output;
    }

    public function convertDataToProfile()
    {
        $type = $this->params->get('type', 'Select');

        if (in_array($type, array('SelectMultiple', 'Checkbox'))) {
            $this->data = LoveFactoryFieldMultipleChoiceInterface::convertDataToProfile($this->data);
        }

        return parent::convertDataToProfile();;
    }

    public function filterData()
    {
        $type = $this->params->get('type', 'Select');

        switch ($type) {
            default:
            case 'Select':
            case 'Radio':
                $this->data = LoveFactoryFieldSingleChoiceInterface::filterData($this->data, $this->getChoices());
                break;

            case 'SelectMultiple':
            case 'Checkbox':
                $this->data = LoveFactoryFieldMultipleChoiceInterface::filterData($this->data, $this->getChoices());
                break;
        }
    }
}
