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

class LoveFactoryFieldText extends LoveFactoryField
{
    protected $generatesDataColumn = true;
    protected $generatesVisibilityColumn = true;

    public function renderInputEdit()
    {
        $data = htmlentities($this->data, ENT_COMPAT, 'UTF-8');

        $disabled = $this->isEditable() ? '' : 'disabled="disabled"';

        return '<input type="text" id="' . $this->getHtmlId() . '" name="' . $this->getHtmlName() . '" value="' . $data . '" ' . $disabled . ' />';
    }

    public function renderInputView()
    {
        if ('' == $this->data || is_null($this->data)) {
            return $this->renderInputBlank();
        }

        return $this->data;
    }

    public function validate()
    {
        if (!parent::validate()) {
            return false;
        }

        // Check if field length is valid
        $length = strlen($this->data);
        $minLength = $this->getParam('min_length', '');
        $maxLength = $this->getParam('max_length', '');

        if ('' != $minLength && $length < $minLength) {
            $this->setError(FactoryText::sprintf('field_text_error_min_length', $this->getLabel(), $minLength));
            return false;
        }

        if ('' != $maxLength && $length > $maxLength) {
            $this->setError(FactoryText::sprintf('field_text_error_max_length', $this->getLabel(), $maxLength));
            return false;
        }

        // Check validation.
        if ('' != $this->data) {
            switch ($this->getParam('validation', '')) {
                case 'numeric':
                    if (!is_numeric($this->data)) {
                        $this->setError(FactoryText::sprintf('field_text_error_numeric', $this->getLabel()));
                        return false;
                    }
                    break;

                case 'custom':
                    $regexp = $this->getParam('validation_regexp', null);

                    if (null !== $regexp && !preg_match($regexp, $this->data)) {
                        $language = JFactory::getLanguage();

                        $messageStandard = FactoryText::sprintf('field_text_error_validation', $this->getLabel());
                        $messageDefault = $this->getParam('validation_regexp_error.default', $messageStandard);
                        $messageLanguage = $this->getParam('validation_regexp_error.' . $language->getTag(), $messageDefault);

                        $this->setError($messageLanguage);
                        return false;
                    }
                    break;
            }
        }

        return true;
    }

    public function getQuerySearchCondition($query)
    {
        if (is_null($this->data)) {
            return false;
        }

        $output = $query->quoteName('p.' . $this->getId()) . ' LIKE ' . $query->quote('%' . $this->data . '%');

        return $output;
    }

    public function filterData()
    {
        $this->data = trim(strip_tags($this->data));
        $this->data = LoveFactoryApplication::getInstance()->filterBannedWords($this->data);

        if ('' == $this->data) {
            $this->data = null;
        }
    }
}
