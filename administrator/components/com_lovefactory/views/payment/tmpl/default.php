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
      method="post" name="adminForm" id="adminForm" class="form-validate">

    <div class="width-50 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::sprintf('JDETAILS', $this->item->id); ?></legend>

            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('details') as $field): ?>
                    <li><?php echo $field->label; ?>
                        <?php echo $field->input; ?></li>
                <?php endforeach; ?>
            </ul>

            <div class="clr"></div>
        </fieldset>
    </div>

    <div class="width-50 fltrt">
        <?php echo JHtml::_('sliders.start', 'payment-sliders-' . $this->item->id, array('useCookie' => 1)); ?>

        <!-- Errors -->
        <?php echo JHtml::_('sliders.panel', JText::_('COM_LOVEFACTORY_PAYMENT_ERRORS'), 'payment-errors'); ?>
        <fieldset class="panelform">
            <?php if ($this->form->getField('errors')): ?>
                <?php echo $this->form->getInput('errors'); ?>
            <?php else: ?>
                <?php echo JText::_('COM_LOVEFACTORY_PAYMENT_NO_ERRORS'); ?>
            <?php endif; ?>
        </fieldset>

        <!-- IPN -->
        <?php echo JHtml::_('sliders.panel', JText::_('COM_LOVEFACTORY_PAYMENT_IPN'), 'payment-ipn'); ?>
        <fieldset class="panelform">
            <?php if ($this->form->getField('data')): ?>
                <?php echo $this->form->getInput('data'); ?>
            <?php endif; ?>
        </fieldset>

        <?php echo JHtml::_('sliders.end'); ?>

        <input type="hidden" name="task" value=""/>
        <?php echo JHtml::_('form.token'); ?>
    </div>

    <div class="clr"></div>
</form>
