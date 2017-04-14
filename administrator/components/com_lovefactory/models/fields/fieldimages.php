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

class JFormFieldFieldImages extends JFormFieldList
{
    protected function getInput()
    {
        JHtml::script('administrator/components/com_lovefactory/assets/js/fields/images.js');

        $choices = $this->form->getValue('params.choices.default');
        $fieldId = $this->form->getValue('id');

        $path = JPATH_SITE . '/media/com_lovefactory/storage/fieldimage/' . $fieldId;
        $src = JUri::root() . 'media/com_lovefactory/storage/fieldimage/' . $fieldId;

        $url = JRoute::_('index.php?option=com_lovefactory&controller=fieldimage&task=upload&field_id=' . $fieldId);

        $html = array();

        $html[] = '<div class="field-images" data-url="' . $url . '">';

        $height = isset($this->value['height']) ? $this->value['height'] : 100;
        $html[] = '<label>Resize new uploaded image to height</label>';
        $html[] = '<input type="text" id="resize_height" name="' . $this->name . '[height]" value="' . $height . '">';

        $width = isset($this->value['width']) ? $this->value['width'] : 100;
        $html[] = '<label>Resize new uploaded image to width</label>';
        $html[] = '<input type="text" id="resize_width" name="' . $this->name . '[width]" value="' . $width . '">';

        foreach ($choices as $id => $choice) {
            $removeUrl = JRoute::_('index.php?option=com_lovefactory&controller=fieldimage&task=remove&field_id=' . $fieldId . '&id=' . $id);
            $exists = (isset($this->value['choices'][$id]) ? (int)$this->value['choices'][$id] : 0);
            $html[] = '<div style="margin-bottom: 20px;">';

            $html[] = '<label><b>' . $choice . '</b></label>';

            $html[] = '<div class="current-image" style="margin-bottom: 10px; display: ' . ($exists ? 'block' : 'none') . '">';
            if ($exists) {
                $html[] = '<img src="' . $src . '/' . $id . '.png" />';
            }
            $html[] = '<div style="margin-top: 5px;"><a href="' . $removeUrl . '" class="btn btn-mini btn-danger">remove current image</a></div>';
            $html[] = '<input type="hidden" name="' . $this->name . '[choices][' . $id . ']" value="' . (isset($this->value['choices'][$id]) ? (int)$this->value['choices'][$id] : 0) . '">';
            $html[] = '</div>';

            $html[] = '<input type="file" accept=".png,.jpeg,.jpg" data-id="' . $id . '">';
            $html[] = '</div>';
        }

        $html[] = '</div>';

        return implode($html);
    }
}
