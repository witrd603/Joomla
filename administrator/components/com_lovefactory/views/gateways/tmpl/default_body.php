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

<tr class="row<?php echo $this->i % 2; ?>">
    <td class="center">
        <?php echo JHtml::_('grid.id', $this->i, $this->item->id); ?>
    </td>

    <td>
        <a href="index.php?option=com_lovefactory&controller=gateway&task=edit&id=<?php echo $this->item->id; ?>">
            <?php echo $this->escape($this->item->title); ?>
        </a>
    </td>

    <td class="order">
        <?php if ('ordering' == $this->listOrder) : ?>
            <?php if ($this->listDirn == 'asc') : ?>
                <span><?php echo $this->pagination->orderUpIcon($this->i, true, 'gateways.orderup', 'JLIB_HTML_MOVE_UP', true); ?></span>
                <span><?php echo $this->pagination->orderDownIcon($this->i, $this->pagination->total, true, 'gateways.orderdown', 'JLIB_HTML_MOVE_DOWN', true); ?></span>
            <?php elseif ($this->listDirn == 'desc') : ?>
                <span><?php echo $this->pagination->orderUpIcon($this->i, true, 'gateways.orderdown', 'JLIB_HTML_MOVE_UP', true); ?></span>
                <span><?php echo $this->pagination->orderDownIcon($this->i, $this->pagination->total, true, 'gateways.orderup', 'JLIB_HTML_MOVE_DOWN', true); ?></span>
            <?php endif; ?>

            <input type="text" name="order[]" size="5" value="<?php echo $this->item->ordering; ?>"
                   class="text-area-order"/>
        <?php else : ?>
            <?php echo $this->item->ordering; ?>
        <?php endif; ?>
    </td>

    <td>
        <?php echo $this->item->element; ?>
    </td>

    <td class="center">
        <?php echo JHtml::_('jgrid.published', $this->item->published, $this->i, 'gateways.', true); ?>
    </td>
</tr>
