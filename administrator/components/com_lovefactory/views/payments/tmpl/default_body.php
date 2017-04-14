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

JLoader::register('JHtmlLoveFactory', JPATH_SITE . '/components/com_lovefactory/lib/html/html.php');

?>

<tr class="row<?php echo $this->i % 2; ?>">
    <td class="center">
        <?php echo JHtml::_('grid.id', $this->i, $this->item->id); ?>
    </td>

    <td>
        <a href="index.php?option=com_lovefactory&controller=payment&task=edit&id=<?php echo $this->item->id; ?>">
            <?php echo $this->escape($this->item->refnumber); ?>
        </a>
    </td>

    <td>
        <?php if ($this->item->username): ?>
            <?php echo $this->escape($this->item->username); ?>
        <?php else: ?>
            -
        <?php endif; ?>
    </td>

    <td class="center">
        <?php if ($this->item->order_id): ?>
            <?php echo $this->escape($this->item->order_id); ?>
        <?php else: ?>
            -
        <?php endif; ?>
    </td>

    <td class="center">
        <?php echo $this->item->gateway; ?>
    </td>

    <td class="right">
        <?php echo JHtml::_('LoveFactory.currency', $this->item->amount, $this->item->currency); ?>
    </td>

    <td class="center">
        <?php echo JHtml::_('date', $this->item->received_at, 'Y-m-d H:i:s'); ?>
    </td>

    <td class="center">
        <?php echo JHtml::_('LoveFactoryAdministrator.orderStatus', $this->item->status, $this->i, '', false); ?>
    </td>

    <td class="center">
        <?php echo $this->item->id; ?>
    </td>
</tr>
