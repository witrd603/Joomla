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

<table class="paramlist admintable">
    <!-- enable_invoices -->
    <?php echo JHtml::_('settings.boolean', 'enable_invoices', 'SETTINGS_ENABLE_INVOICES', $this->settings->enable_invoices); ?>

    <!-- invoice_vat_rate -->
    <tr class="hasTip groups_related"
        title="<?php echo JText::_('Invoice VAT rate'); ?>::<?php echo JText::_('Invoice VAT rate'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="invoice_vat_rate"><?php echo JText::_('Invoice VAT rate'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" id="invoice_vat_rate" name="invoice_vat_rate"
                   value="<?php echo $this->settings->invoice_vat_rate; ?>"/>
        </td>
    </tr>
</table>
