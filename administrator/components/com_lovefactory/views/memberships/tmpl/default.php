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

    .icon-32-creditcard_paypal {
        background-image: url(<?php echo JURI::root(); ?>components/com_lovefactory/assets/images/creditcard_paypal.png);
        background-position: center;
        width: 48px !important;
    }

    .icon-back:before {
        content: "\e008";
    }
</style>

<form action="index.php?option=com_lovefactory&view=memberships" method="post" name="adminForm" id="adminForm">

    <table width="100%">
        <tr>
            <td align="left" width="50%">
            </td>
            <td style="text-align: right;">
                <?php echo JHTML::_('grid.state', $this->lists['state']); ?>
            </td>
        </tr>
    </table>

    <table class="adminlist table table-striped table-hover">
        <thead>
        <tr>
            <th width="20px"><?php echo JText::_('NUM'); ?></th>
            <th width="20px"><input type="checkbox" name="checkall-toggle" value=""
                                    title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
                                    onclick="Joomla.checkAll(this)"/></th>
            <th class="title"><?php echo JHTML::_('grid.sort', JText::_('MEMBERSHIPS_MEMBERSHIP'), 'm.title', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="60px"><?php echo JHTML::_('grid.sort', JText::_('MEMBERSHIPS_PUBLISHED'), 'm.published', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="50px"><?php echo JHTML::_('grid.sort', JText::_('MEMBERSHIPS_USERS'), 'users', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="100px">
                <?php echo JHTML::_('grid.sort', 'Order', 'm.ordering', $this->lists['order_Dir'], $this->lists['order']); ?>
                <?php if ($this->ordering) echo JHTML::_('grid.order', $this->memberships); ?>
            </th>
            <th width="50px"><?php echo JText::_('MEMBERSHIPS_DEFAULT'); ?></th>
            <th width="20px"><?php echo JHTML::_('grid.sort', JText::_('MEMBERSHIPS_ID'), 'm.id', $this->lists['order_Dir'], $this->lists['order']); ?></th>
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
        <?php foreach ($this->memberships as $i => $membership): ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td width="20px"><?php echo($i + 1 + $this->pagination->limitstart); ?></td>
                <td width="20px"><?php echo JHTML::_('grid.id', $i, $membership->id); ?></td>
                <td>
                    <a href="<?php echo JRoute::_('index.php?option=com_lovefactory&controller=membership&task=edit&cid[]=' . $membership->id); ?>">
                        <?php echo $membership->title; ?>
                    </a>
                </td>

                <td style="text-align: center;" class="jgrid">
                    <?php echo JHtml::_('jgrid.published', $membership->published, $i, '', false); ?>
                </td>

                <td style="text-align: center;"><?php echo $membership->users; ?></td>
                <td class="order">
                    <span
                        style="width: 25px; display: inline-block;"><?php echo $this->pagination->orderUpIcon($i, true, 'orderup', 'Move Up', $this->ordering); ?></span>
                    <span
                        style="width: 25px; display: inline-block;"><?php echo $this->pagination->orderDownIcon($i, count($this->memberships), true, 'orderdown', 'Move Down', $this->ordering); ?></span>
                    <input type="text" name="order[]" size="1" style="width: 20px;"
                           value="<?php echo $membership->ordering; ?>" <?php echo $this->ordering ? '' : 'disabled'; ?>
                           class="text_area" style="text-align: center"/>
                </td>
                <td style="text-align: center;" class="jgrid">
                    <?php echo JHtml::_('jgrid.published', $membership->default, $i, '', false); ?>
                </td>
                <td style="text-align: center;"><?php echo $membership->id; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <input type="hidden" name="controller" value="membership"/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value="memberships"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>

<script>
    Joomla.submitbutton = function (pressbutton) {

        if ('options' == pressbutton) {
            SqueezeBox.initialize({});

            //var options = $merge(options || {}, "{handler: 'iframe', size: {x: 800, y: 450}}");
            var options = "{handler: 'iframe', size: {x: 800, y: 450}}";
            SqueezeBox.setOptions(SqueezeBox.presets, options);
            SqueezeBox.assignOptions();
            SqueezeBox.setContent('iframe', 'index.php?option=com_lovefactory&task=membershipsoptions&tmpl=component');
            return false;
        }

        Joomla.submitform(pressbutton);
    }
</script>
