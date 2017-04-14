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

<tr>
    <th width="1%">
        <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
               onclick="Joomla.checkAll(this)"/>
    </th>

    <th>
        <?php echo JHtml::_('grid.sort', 'COM_LOVEFACTORY_INVOICES_LIST_MEMBERSHIP', 'i.membership', $this->listDirn, $this->listOrder); ?>
    </th>

    <th width="10%">
        <?php echo JHtml::_('grid.sort', 'COM_LOVEFACTORY_INVOICES_LIST_USERNAME', 'u.username', $this->listDirn, $this->listOrder); ?>
    </th>

    <th width="8%">
        <?php echo JHtml::_('grid.sort', 'COM_LOVEFACTORY_INVOICES_LIST_PRICE', 'i.price', $this->listDirn, $this->listOrder); ?>
    </th>

    <th width="8%">
        <?php echo JHtml::_('grid.sort', 'COM_LOVEFACTORY_INVOICES_LIST_VAT', 'i.vat_value', $this->listDirn, $this->listOrder); ?>
    </th>

    <th width="8%">
        <?php echo JHtml::_('grid.sort', 'COM_LOVEFACTORY_INVOICES_LIST_TOTAL', 'i.total', $this->listDirn, $this->listOrder); ?>
    </th>

    <th width="10%">
        <?php echo JHtml::_('grid.sort', 'COM_LOVEFACTORY_INVOICES_LIST_ISSUES_AT', 'i.issued_at', $this->listDirn, $this->listOrder); ?>
    </th>
</tr>
