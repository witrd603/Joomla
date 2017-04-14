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

<style>
    .icon-48-generic {
        background-image: url(<?php echo JURI::root(); ?>components/com_lovefactory/assets/images/love.png);
    }
</style>

<form action="index.php?option=com_lovefactory&view=approvals" method="post" name="adminForm" id="adminForm">

    <table class="adminlist table table-hover table-striped">
        <thead>
        <tr>
            <th width="20px"><?php echo JText::_('NUM'); ?></th>
            <th width="20px">
                <input type="checkbox" name="checkall-toggle" value=""
                       title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
            </th>
            <th><?php echo JHTML::_('grid.sort', JText::_('APPROVALS_USERNAME'), 'username', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="10%"><?php echo JHTML::_('grid.sort', JText::_('APPROVALS_TYPE'), 'type', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="15%"><?php echo JHTML::_('grid.sort', JText::_('APPROVALS_DATE'), 'created_at', $this->lists['order_Dir'], $this->lists['order']); ?></th>
        </tr>
        </thead>

        <tfoot>
        <tr>
            <td colspan="20">
                <?php echo $this->pagination->getLimitBox(); ?><?php echo $this->pagination->getListFooter(); ?>
            </td>
        </tr>
        </tfoot>

        <tbody>
        <?php foreach ($this->items as $i => $this->item): ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td><?php echo($i + 1 + $this->pagination->limitstart); ?></td>
                <td><?php echo JHTML::_('grid.id', $i, $this->item->type . '.' . $this->item->item_id); ?></td>
                <td>
                    <a href="<?php echo JRoute::_('index.php?option=com_lovefactory&controller=approval&task=review&cid[]=' . $this->item->type . '.' . $this->item->item_id); ?>"><?php echo $this->item->username; ?></a>
                </td>
                <td class="center"><?php echo $this->item->type; ?></td>
                <td class="center"><?php echo JHtml::date($this->item->created_at, 'Y-m-d H:i:s'); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <input type="hidden" name="controller" value="approval"/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value="approvals"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
