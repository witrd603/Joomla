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

class ViewableFieldRenderer extends FieldRendererInterface
{
    protected $mode = 'view';

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
        $html[] = $field->renderViewable();
        $html[] = '</div>';

        $html[] = '</div>';

        return implode("\n", $html);
    }
}
