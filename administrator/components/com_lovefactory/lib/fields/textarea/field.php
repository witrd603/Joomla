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

class LoveFactoryFieldTextarea extends LoveFactoryField
{
    protected $generatesDataColumn = true;
    protected $generatesVisibilityColumn = true;

    public function __construct($type, $field = null, $mode = 'view')
    {
        parent::__construct($type, $field, $mode);

        if ($this->params->get('allowed_tags', '')) {
            $this->addHelpText(FactoryText::sprintf('field_textarea_allowed_tags', $this->params->get('allowed_tags', '')), 'fa-code');
        }
    }

    public function renderInputEdit()
    {
        $style = array();

        $cols = $this->params->get('cols', '');
        $rows = $this->params->get('rows', '');

        if ('' != $cols) {
            $style[] = 'width: auto';
        }

        if ('' != $rows) {
            $style[] = 'height: auto';
        }

        $html = array();

        $html[] = '<textarea style="' . implode(';', $style) . '" id="' . $this->getHtmlId() . '" name="' . $this->getHtmlName() . '" rows="' . $rows . '" cols="' . $cols . '">' . $this->data . '</textarea>';

        return implode("\n", $html);
    }

    public function renderInputView()
    {
        if ('' == $this->data || is_null($this->data)) {
            return $this->renderInputBlank();
        }

        return nl2br($this->data);
    }

    public function renderInputSearch()
    {
        $data = htmlentities($this->data, ENT_COMPAT, 'UTF-8');

        return '<input type="text" id="' . $this->getHtmlId() . '" name="' . $this->getHtmlName() . '" value="' . $data . '" />';
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
        $tags = explode(',', $this->params->get('allowed_tags', ''));

        foreach ($tags as &$tag) {
            $tag = trim($tag);
        }

        $this->data = trim(strip_tags($this->data, '<' . implode('><', $tags) . '>'));
        $this->data = LoveFactoryApplication::getInstance()->filterBannedWords($this->data);

        if ('' == $this->data) {
            $this->data = null;
        }
    }
}
