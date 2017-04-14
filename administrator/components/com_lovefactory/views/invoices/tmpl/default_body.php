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
        <a href="#"
           onclick="window.open('<?php echo JRoute::_('index.php?option=com_lovefactory&view=invoice&tmpl=component&id=' . $this->item->id); ?>', 'lovefactory-invoice', 'width=800, height=600'); return false;">
            <?php echo $this->escape($this->item->membership); ?>
        </a>
    </td>

    <td class="center">
        <?php echo $this->escape($this->item->username); ?>
    </td>

    <td class="right">
        <?php echo JHtml::_('LoveFactory.currency', $this->item->price, $this->item->currency); ?>
    </td>

    <td class="right">
        <?php echo JHtml::_('LoveFactory.currency', $this->item->vat_value, $this->item->currency); ?>
    </td>

    <td class="right">
        <?php echo JHtml::_('LoveFactory.currency', $this->item->total, $this->item->currency); ?>
    </td>

    <td class="center">
        <?php echo JHtml::date($this->item->issued_at, 'Y-m-d H:i:s'); ?>
    </td>
</tr>
