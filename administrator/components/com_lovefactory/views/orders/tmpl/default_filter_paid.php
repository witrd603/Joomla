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

<select name="filter_paid" class="inputbox" onchange="this.form.submit()">
    <option value=""><?php echo JText::_('COM_LOVEFACTORY_ORDERS_FILTER_PAID_LABEL'); ?></option>
    <?php echo JHtml::_('select.options',
        array(0 => JText::_('COM_LOVEFACTORY_ORDERS_FILTER_PAID_NOT_PAID'), 1 => JText::_('COM_LOVEFACTORY_ORDERS_FILTER_PAID_PAID')),
        'value',
        'text',
        $this->state->get('filter.paid'),
        true); ?>
</select>
