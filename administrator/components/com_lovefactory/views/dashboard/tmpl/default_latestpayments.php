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

<div class="box" id="portlet_latestpayments">
    <div class="header2">
        <?php echo FactoryText::_('dashboard_box_latest_payments'); ?>
        <span>(<a
                href="<?php echo FactoryRoute::task('payments'); ?>"><?php echo FactoryText::_('dashboard_latest_media_view_all'); ?></a>)</span>
        <div style="display: none;" class="factory-icon icon-minus-circle minimize"></div>
    </div>
    <div class="content">

        <?php if ($this->latestPayments): ?>
            <table>
                <thead>
                <tr>
                    <th><?php echo FactoryText::_('dashboard_latest_payments_list_refnumber'); ?></th>
                    <th width="100"
                        style="text-align:right;"><?php echo FactoryText::_('dashboard_latest_payments_list_amount'); ?></th>
                    <th width="120"><?php echo FactoryText::_('dashboard_latest_payments_list_status'); ?></th>
                    <th width="140"><?php echo FactoryText::_('dashboard_latest_payments_list_date'); ?></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($this->latestPayments as $this->i => $item): ?>
                    <tr class="<?php echo $this->i % 2 ? '' : 'even'; ?>">
                        <td>
                            <a href="<?php echo FactoryRoute::_('controller=payment&task=edit&id=' . $item->id); ?>"><?php echo $item->refnumber; ?></a>
                        </td>
                        <td class="right">
                            <?php echo JHtml::_('LoveFactory.currency', $item->amount, $item->currency); ?>
                        </td>
                        <td><?php echo JHtml::_('LoveFactoryAdministrator.orderLabel', $item->status); ?></td>
                        <td><?php echo JHtml::date($item->received_at, 'Y-m-d H:i:s'); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p><?php echo FactoryText::_('dashboard_list_no_items'); ?></p>
        <?php endif; ?>

    </div>
</div>
