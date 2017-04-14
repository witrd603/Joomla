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
use ThePhpFactory\LoveFactory\Factory;

class EditableFieldRenderer extends FieldRendererInterface
{
    protected $mode = 'edit';

    public function render(Field $field)
    {
        $html = array();

        $html[] = '<div class="control-group ' . implode(' ', $this->getClasses($field, $this->mode)) . '">';

        if ($error = $field->getError()) {
            $html[] = $this->renderError($error);
        }

        if ($field->hasLabel($this->mode)) {
            $html[] = $this->renderLabel($field);
        }

        $html[] = $this->renderField($field);

        $html[] = '</div>';

        return implode("\n", $html);
    }

    protected function renderLabel($field)
    {
        \JHtml::_('bootstrap.tooltip');

        $required = $field->isRequired() ? '<span class="required">*</span>' : '';
        $label = $field->getLabel();
        $id = $field->getHtmlId();
        $tooltip = '<b>' . $label . '</b>';

        if ($field->showDescription() && '' !== $description = $field->getDescription()) {
            $tooltip .= '<br />' . $description;
        }

        return '<label class="control-label hasTooltip" title="' . $tooltip . '" for="' . $id . '">' . $label . $required . '</label>';
    }

    protected function renderField($field)
    {
        $html = array();

        $html[] = '<div class="controls">';

        $html[] = '<div class="control-field">';
        $html[] = $field->renderEditable();
        $html[] = '</div>';

        $html[] = '<ul class="field-info small muted">';
        $html[] = '<li>' . $this->renderLockStatus($field) . '</li>';
        $html[] = '<li>' . $this->renderPrivacy($field) . '</li>';
        $html[] = '<li>' . $this->renderDescription($field) . '</li>';

        foreach ($field->getHelpText() as $help) {
            $html[] = '<li>' . $this->renderHelpText($help) . '</li>';
        }

        $html[] = '</ul>';

        $html[] = '</div>';

        return implode("\n", $html);
    }

    protected function renderPrivacy($field)
    {
        $html = array();

        $html[] = '<span class="privacy"><span class="fa fa-shield fa-fw"></span>';

        // Check if field is viewable only by the administrators.
        if ($field->isAdminOnlyViewable()) {
            $html[] = \FactoryText::_('field_viewable_only_by_administrators');
        } // Check if field privacy is disabled for users.
        elseif (!$field->hasUserPrivacy()) {
            // If field privacy is disabled for users and privacy is set to public,
            // do not show anyting, this is the default state.
            if ('public' === $field->getPrivacy()) {
                return;
            }

            // Field privacy is disabled, but the admin has set it to something different
            // than public. In this case, show the privacy as read-only!
            $html[] = \FactoryText::_('field_privacy_label') . ' <b class="hasTooltip locked-privacy" title="' . \FactoryText::_('field_privacy_set_by_administrator') . '">' . ucfirst($field->getPrivacy()) . '</b></span>';
        } // Field privacy is enabled for the user, show update field.
        else {
            \FactoryHtml::script('views/edit');
            $privacy = $field->getPrivacy();
            $options = array(
                'public' => \FactoryText::_('field_privacy_public'),
                'friends' => \FactoryText::_('field_privacy_friends'),
                'private' => \FactoryText::_('field_privacy_private'),
            );
            $select = \JHtml::_('select.genericlist', $options, $field->getHtmlVisibilityName(), 'data-rel="privacy"', '', '', $privacy);

            $html[] = \FactoryText::_('field_privacy_label') . ' <a href="#" data-rel="privacy"><b class="hasTooltip" title="' . \FactoryText::_('field_privacy_tooltip') . '">' . \FactoryText::_('field_privacy_' . $privacy) . '</b></a>' . $select;
        }

        $html[] = '</span>';

        return implode('', $html);
    }

    protected function renderDescription($field)
    {
        // Do not show the more information text if description is not set.
        if ('' === $tooltip = $field->getDescription()) {
            return;
        }

        $html = array();

        $html[] = '<div class="visible-phone visible-tablet"><span class="fa fa-question-circle fa-fw"></span>' . $tooltip . '</div>';
        $html[] = '<div class="hidden-phone hidden-tablet"><span class="hasTooltip description" title="' . $tooltip . '"><span class="fa fa-question-circle fa-fw"></span>' . \FactoryText::_('field_more_information') . '</span></div>';

        return implode("\n", $html);
    }

    protected function renderLockStatus($field)
    {
        // Do not show the lock information if the field is not locked after save.
        if (!$field->isLockedAfterSave()) {
            return;
        }

        if ($field->hasBeenLockedAfterSave()) {
            return '<span><span class="fa fa-lock fa-fw"></span>' . \FactoryText::_('field_locked') . '</span>';
        }

        return '<span class="lock-status"><span class="fa fa-lock fa-fw"></span>' . \FactoryText::_('field_locked_tooltip') . '</span>';
    }

    protected function renderError($error)
    {
        return '<div class="field-error"><span class="fa fa-warning"></span>' . $error . '</div>';
    }

    protected function renderHelpText($help)
    {
        return '<span class="fa fa-fw ' . $help['icon'] . '"></span>' . $help['message'];
    }
}
