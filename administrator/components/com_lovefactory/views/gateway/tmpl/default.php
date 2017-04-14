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

?>

<form action="<?php echo JRoute::_('index.php?option=com_lovefactory&layout=edit&id=' . (int)$this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
    <div class="width-60 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('JDETAILS'); ?></legend>

            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('details') as $field): ?>
                    <li><?php echo $field->label; ?>
                        <?php echo $field->input; ?></li>
                <?php endforeach; ?>
            </ul>
        </fieldset>

        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_LOVEFACTORY_GATEWAY_PARAMS'); ?></legend>

            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('params') as $field): ?>
                    <li><?php echo $field->label; ?>
                        <?php echo $field->input; ?></li>
                <?php endforeach; ?>
            </ul>
        </fieldset>
    </div>

    <div class="width-40 fltrt">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_LOVEFACTORY_GATEWAY_INFO'); ?></legend>

            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('info') as $field): ?>
                    <li style="list-style-type: circle;"><?php echo $field->input; ?></li>
                <?php endforeach; ?>
            </ul>
        </fieldset>
    </div>

    <input type="hidden" name="controller" value=""/>
    <input type="hidden" name="task" value=""/>
    <?php echo JHtml::_('form.token'); ?>
</form>
