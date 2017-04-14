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
        <?php echo JHtml::_('grid.sort', 'COM_LOVEFACTORY_ORDERS_LIST_TITLE', 'o.title', $this->listDirn, $this->listOrder); ?>
    </th>

    <th width="8%">
        <?php echo JHtml::_('grid.sort', 'COM_LOVEFACTORY_ORDERS_LIST_USERNAME', 'u.username', $this->listDirn, $this->listOrder); ?>
    </th>

    <th width="8%">
        <?php echo JHtml::_('grid.sort', 'COM_LOVEFACTORY_ORDERS_LIST_MEMBERSHIP', 'm.title', $this->listDirn, $this->listOrder); ?>
    </th>

    <th width="8%">
        <?php echo JHtml::_('grid.sort', 'COM_LOVEFACTORY_ORDERS_LIST_GATEWAY', 'o.gateway', $this->listDirn, $this->listOrder); ?>
    </th>

    <th width="8%">
        <?php echo JHtml::_('grid.sort', 'COM_LOVEFACTORY_ORDERS_LIST_AMOUNT', 'o.amount', $this->listDirn, $this->listOrder); ?>
    </th>

    <th width="8%">
        <?php echo JHtml::_('grid.sort', 'COM_LOVEFACTORY_ORDERS_LIST_CREATED_AT', 'o.created_at', $this->listDirn, $this->listOrder); ?>
    </th>

    <th width="5%">
        <?php echo JHtml::_('grid.sort', 'COM_LOVEFACTORY_ORDERS_LIST_PAID', 'o.paid', $this->listDirn, $this->listOrder); ?>
    </th>

    <th width="5%">
        <?php echo JHtml::_('grid.sort', 'COM_LOVEFACTORY_ORDERS_LIST_STATUS', 'o.status', $this->listDirn, $this->listOrder); ?>
    </th>

    <th width="3%">
        <?php echo JHtml::_('grid.sort', 'ID', 'o.id', $this->listDirn, $this->listOrder); ?>
    </th>
</tr>
