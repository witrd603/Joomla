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

<div class="box" id="portlet_latestorders">
    <div class="header2">
        <?php echo FactoryText::_('dashboard_box_latest_orders'); ?>
        <span>(<a
                href="<?php echo FactoryRoute::task('orders'); ?>"><?php echo FactoryText::_('dashboard_latest_media_view_all'); ?></a>)</span>
        <div style="display: none;" class="factory-icon icon-minus-circle minimize"></div>
    </div>
    <div class="content">

        <?php if ($this->latestOrders): ?>
            <table>
                <thead>
                <tr>
                    <th><?php echo FactoryText::_('dashboard_latest_order_list_title'); ?></th>
                    <th width="100"><?php echo FactoryText::_('dashboard_latest_orders_list_gateway'); ?></th>
                    <th width="120"
                        style="text-align:right;"><?php echo FactoryText::_('dashboard_latest_orders_list_amount'); ?></th>
                    <th width="140"><?php echo FactoryText::_('dashboard_latest_orders_list_date'); ?></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($this->latestOrders as $this->i => $item): ?>
                    <tr class="<?php echo $this->i % 2 ? '' : 'even'; ?>">
                        <td>
                            <a href="<?php echo FactoryRoute::_('controller=order&task=edit&id=' . $item->id); ?>"><?php echo $item->title; ?></a>
                        </td>
                        <td><?php echo $item->gateway; ?></td>
                        <td style="text-align: right;">
                            <?php echo JHtml::_('LoveFactory.currency', $item->amount, $item->currency); ?>
                        </td>
                        <td><?php echo JHtml::date($item->created_at, 'Y-m-d H:i:s'); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p><?php echo FactoryText::_('dashboard_list_no_items'); ?></p>
        <?php endif; ?>

    </div>
</div>
