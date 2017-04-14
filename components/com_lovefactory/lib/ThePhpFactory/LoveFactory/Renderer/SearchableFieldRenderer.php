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

namespace ThePhpFactory\LoveFactory\Renderer;

defined('_JEXEC') or die;

use LoveFactoryField as Field;

class SearchableFieldRenderer extends FieldRendererInterface
{
    protected $mode = 'search';

    public function render(Field $field)
    {
        $html = array();

        $html[] = '<div class="control-group ' . implode(' ', $this->getClasses($field, $this->mode)) . '">';

        if ($field->hasLabel($this->mode)) {
            $html[] = $this->renderLabel($field);
        }

        $html[] = $this->renderField($field);

        $html[] = '</div>';

        return implode("\n", $html);
    }

    protected function renderLabel(Field $field)
    {
        \JHtml::_('bootstrap.tooltip');

        $label = $field->getLabel();
        $id = $field->getHtmlId();
        $tooltip = '<b>' . $label . '</b>';

        if ($field->showDescription() && '' !== $description = $field->getDescription()) {
            $tooltip .= '<br />' . $description;
        }

        return '<label class="control-label hasTooltip" title="' . $tooltip . '" for="' . $id . '">' . $label . '</label>';
    }

    protected function renderField(Field $field)
    {
        $html = array();

        $html[] = '<div class="controls">';

        $html[] = '<div class="control-field">';
        $html[] = $field->renderSearchable();
        $html[] = '</div>';

        $html[] = '<ul class="field-info small muted">';
        $html[] = '<li>' . $this->renderDescription($field) . '</li>';
        $html[] = '</ul>';

        $html[] = '</div>';

        return implode("\n", $html);
    }

    protected function renderDescription(Field $field)
    {
        // Do not show the more information text if description is not set.
        if ('' === $tooltip = $field->getDescription()) {
            return;
        }

        return '<span class="hasTooltip description" title="' . $tooltip . '"><span class="fa fa-question-circle fa-fw"></span>' . \FactoryText::_('field_more_information') . '</span>';
    }
}
