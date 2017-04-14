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

JHtml::stylesheet('administrator/components/com_lovefactory/assets/css/main.css');

?>

<script>
    Joomla.submitbutton = function (pressbutton) {
        var split = pressbutton.split('.');

        document.getElementById('controller').value = split[0];
        pressbutton = split[1];

        Joomla.submitform(pressbutton);
    }

    jQuery(document).ready(function ($) {
        // Text field.
        $('select#jform_params_validation').change(function () {
            if ('custom' == $(this).val()) {
                $('.validation_custom').parents('li').show();
            }
            else {
                $('.validation_custom').parents('li').hide();
            }
        }).change();
    });
</script>

<form action="<?php echo JRoute::_('index.php?option=com_lovefactory&view=field&id=' . (int)$this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">

    <div class="width-50 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('JDETAILS'); ?></legend>

            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('details') as $field): ?>
                    <li><?php echo $field->label; ?>
                        <?php echo $field->input; ?></li>
                <?php endforeach; ?>
            </ul>

            <div class="clr"></div>
        </fieldset>
    </div>

    <div class="width-50 fltlft">
        <?php echo JHtml::_('sliders.start', 'field-sliders-' . $this->item->id, array('useCookie' => 1)); ?>
        <?php echo JHtml::_('sliders.panel', JText::_('COM_LOVEFACTORY_FORM_FIELD_SETTINGS'), 'settings'); ?>
        <fieldset class="panelform">
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('settings') as $field): ?>
                    <li><?php echo $field->label; ?>
                        <?php echo $field->input; ?></li>
                <?php endforeach; ?>
            </ul>
        </fieldset>

        <?php if ($this->form->getFieldset('params')): ?>
            <?php echo JHtml::_('sliders.panel', JText::_('COM_LOVEFACTORY_FORM_FIELD_PARAMETERS'), 'parameters'); ?>
            <fieldset class="panelform">
                <ul class="adminformlist">
                    <?php foreach ($this->form->getFieldset('params') as $field): ?>
                        <li><?php echo $field->label; ?>
                            <?php echo $field->input; ?></li>
                    <?php endforeach; ?>
                </ul>
            </fieldset>

            <?php foreach ($this->form->subparams as $subparam): ?>
                <?php echo JHtml::_('sliders.panel', FactoryText::_('form_field_subparams_' . $this->form->getValue('type') . '_' . $subparam), 'subparams_' . $this->form->getValue('type') . '_' . $subparam); ?>
                <fieldset class="panelform">
                    <ul class="adminformlist">
                        <?php foreach ($this->form->getFieldset('params_' . $subparam) as $field): ?>
                            <li><?php echo $field->label; ?>
                                <?php echo $field->input; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </fieldset>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php echo JHtml::_('sliders.panel', JText::_('COM_LOVEFACTORY_FORM_FIELD_LABEL'), 'labels'); ?>
        <fieldset class="panelform">
            <ul class="adminformlist">
                <li><?php echo $this->form->getInput('labels'); ?></li>
            </ul>
        </fieldset>

        <?php echo JHtml::_('sliders.panel', JText::_('COM_LOVEFACTORY_FORM_FIELD_DESCRIPTION'), 'descriptions'); ?>
        <fieldset class="panelform">
            <ul class="adminformlist">
                <li><?php echo $this->form->getInput('descriptions'); ?></li>
            </ul>
        </fieldset>

        <?php echo JHtml::_('sliders.panel', JText::_('COM_LOVEFACTORY_FORM_FIELD_CUSTOM_CSS'), 'custom-css'); ?>
        <fieldset class="panelform">
            <ul class="adminformlist">
                <li><?php echo $this->form->getInput('css'); ?></li>
            </ul>
        </fieldset>

        <?php echo JHtml::_('sliders.end'); ?>
    </div>

    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="controller" id="controller" value=""/>
    <?php echo JHtml::_('form.token'); ?>

    <div class="clr"></div>
</form>
