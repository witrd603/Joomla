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

<form action="index.php?option=com_lovefactory&view=pricing" method="post" name="adminForm" id="adminForm">

    <table width="100%">
        <tr>
            <td align="left" width="50%">
            </td>
            <td style="text-align: right;">
                <?php echo JHTML::_('select.genericlist', $this->lists['memberships'], 'membership', 'size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->lists['membership']); ?>
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
            <th class="title"><?php echo JHTML::_('grid.sort', JText::_('PRICING_MEMBERSHIP'), 'p.membership_id', $this->lists['order_Dir'], $this->lists['order']); ?></th>

            <?php if (!$this->settings->gender_pricing): ?>
                <th width="8%"><?php echo JHTML::_('grid.sort', JText::_('PRICING_PRICE'), 'p.price', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <?php endif; ?>

            <th width="8%"><?php echo JHTML::_('grid.sort', JText::_('PRICING_INTERVAL'), 'p.months', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="8%"><?php echo JHTML::_('grid.sort', JText::_('PRICING_TRIAL'), 'p.is_trial', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="12%"><?php echo JHTML::_('grid.sort', JText::_('PRICING_PUBLISHED'), 'p.published', $this->lists['order_Dir'], $this->lists['order']); ?></th>
        </tr>
        </thead>

        <tfoot>
        <tr>
            <td colspan="12">
                <?php echo $this->pagination->getLimitBox(); ?><?php echo $this->pagination->getListFooter(); ?>
            </td>
        </tr>
        </tfoot>

        <tbody>
        <?php foreach ($this->prices as $i => $price): ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td width="20px"><?php echo($i + 1 + $this->pagination->limitstart); ?></td>
                <td width="20px"><?php echo JHTML::_('grid.id', $i, $price->id); ?></td>
                <td>
                    <a href="<?php echo JRoute::_('index.php?option=com_lovefactory&controller=price&task=edit&cid[]=' . $price->id); ?>">
                        <?php echo $price->title; ?>
                    </a>
                </td>

                <?php if (!$this->settings->gender_pricing): ?>
                    <td style="text-align: <?php echo $price->is_trial ? 'center' : 'right'; ?>;">
                        <?php if ($price->is_trial): ?>
                            -
                        <?php else: ?>
                            <?php echo JHtml::_('LoveFactory.currency', $price->price, $this->settings->currency); ?>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>

                <td style="text-align: center;">
                    <?php if ($price->months): ?>
                        <?php echo $price->months; ?>
                    <?php else: ?>
                        <?php echo JText::_('PRICING_UNLIMITED'); ?>
                    <?php endif; ?>
                </td>

                <td style="text-align: center;" class="jgrid">
                    <?php echo JHtml::_('jgrid.published', $price->is_trial, $i, '', false); ?>
                </td>

                <td style="text-align: center;" class="jgrid">
                    <?php echo JHtml::_('jgrid.published', $price->published, $i, '', false); ?>
                </td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <input type="hidden" name="controller" value="price"/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value="pricing"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>

<script>
    Joomla.submitbutton = function (pressbutton) {

        if ('options' == pressbutton) {
            SqueezeBox.initialize({});

            var options = "{handler: 'iframe', size: {x: 800, y: 450}}";
            SqueezeBox.setOptions(SqueezeBox.presets, options);
            SqueezeBox.assignOptions();
            SqueezeBox.setContent('iframe', 'index.php?option=com_lovefactory&task=pricingoptions&tmpl=component');
            return false;
        }

        Joomla.submitform(pressbutton);
    }
</script>
