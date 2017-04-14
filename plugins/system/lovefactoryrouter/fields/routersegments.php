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

class JFormFieldRouterSegments extends JFormField
{
    protected function getInput()
    {
        if (!$this->value || !is_array($this->value)) {
            $this->value = array();
        }

        $options = $this->getOptions();

        $html = array();

        $html[] = '<div data-field="segments" data-template="' . htmlentities($this->getSelect($this->name, $options)) . '">';

        $html[] = '<div class="segments">';
        for ($i = 0, $count = count($this->value); $i < $count; $i++) {
            $html[] = $this->getSelect($this->name, $options, $this->value[$i]);
        }
        $html[] = '</div>';

        $html[] = '<div style="margin-top:10px;">';
        $html[] = '<a href="#" class="btn btn-small btn-success" data-button="add">Add segment</a>';
        $html[] = '</div>';

        $html[] = '</div>';

        JFactory::getDocument()->addScriptDeclaration(
            <<<JS
            jQuery(document).ready(function ($) {
  var segments = $('[data-field="segments"]');

  // Add new segment button.
  $('[data-button="add"]', '[data-field="segments"]').click(function (event) {
    event.preventDefault();

    var template = $('[data-field="segments"]').data('template');
    var ul       = $('div.segments', '[data-field="segments"]');

    ul.append(template);

    ul.find('select[class!="chzn-done"]').val('').chosen({
      "disable_search_threshold": 10,
      "allow_single_deselect": true,
      "placeholder_text_multiple": "Select some options",
      "placeholder_text_single": "Select an option",
      "no_results_text": "No results match"
    });

    segments.trigger('refresh');
  });

  // Remove existing segment button.
  segments.on('click', 'a[data-button="remove"]', function (event) {
    event.preventDefault();

    $(this).parents('div.segment:first').remove();

    segments.trigger('refresh');
  });

  // Refresh dropdowns.
  $(document).on('refresh', '[data-field="segments"]', function () {
    var segments = $(this);

    segments.find('select').each(function (index, element) {
      if (0 === index) {
        var reselect = 'Text' === $(element).find('option:selected').data('type');

        $(element).find('option[data-type="Text"]').remove();

        if (reselect) {
          $(element).val('');
        }

        $(element).trigger('liszt:updated');
      }
    });
  });

  segments.trigger('refresh');
});
JS
        );

        return implode("\n", $html);
    }

    protected function getSelect($name, array $options = array(), $value = null)
    {
        $html = array();

        $html[] = '<div class="segment" style="margin-bottom: 10px;">';

        $html[] = '<select name="' . $name . '[]">';

        $html[] = '<option value=""></option>';

        foreach ($options as $option) {
            $selected = $option['id'] == $value ? 'selected="selected"' : '';

            $html[] = '<option value="' . $option['id'] . '" ' . $selected . ' data-type="' . $option['type'] . '">';
            $html[] = $option['title'];
            $html[] = '</option>';
        }

        $html[] = '</select>';

        $html[] = '<a href="#" class="btn btn-mini btn-danger" data-button="remove">Remove</a>';

        $html[] = '</div>';

        return implode("\n", $html);
    }

    protected function getOptions()
    {
        $dbo = JFactory::getDbo();
        $types = array('Text', 'Select', 'Checkbox', 'Radio', 'SelectMultiple', 'Gender');

        $query = $dbo->getQuery(true)
            ->select('f.id, f.title, f.type')
            ->from($dbo->qn('#__lovefactory_fields', 'f'))
            ->where('f.published = ' . $dbo->q(1))
            ->where('f.type IN (' . implode(',', $dbo->q($types)) . ')')
            ->order('f.title ASC');

        $results = $dbo->setQuery($query)
            ->loadAssocList();

        return $results;
    }
}
