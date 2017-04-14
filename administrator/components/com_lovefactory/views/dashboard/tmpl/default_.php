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

<style>
    .icon-48-generic {
        background-image: url(<?php echo JURI::root(); ?>components/com_lovefactory/assets/images/love.png);
    }
</style>

<table class="admintable" width="100%">
    <tr valign="top">
        <td width="50%">
            <fieldset>
                <legend><?php echo JText::_('DASHBOARD_USERS'); ?></legend>
                <?php require_once('_users.php'); ?>
            </fieldset>

            <fieldset>
                <legend><?php echo JText::_('DASHBOARD_LATESAT_PAYMENTS'); ?></legend>

                <table class="adminlist">
                    <thead>
                    <tr>
                        <th style="width: 140px;"><?php echo JText::_('DASHBOARD_RECEIVED_AT'); ?></th>
                        <th><?php echo JText::_('DASHBOARD_USER'); ?></th>
                        <th width="18%"><?php echo JText::_('DASHBOARD_AMOUNT'); ?></th>
                        <th width="8%"
                        "><?php echo JText::_('DASHBOARD_STATUS'); ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (count($this->payments)): ?>
                        <?php foreach ($this->payments as $i => $payment): ?>
                            <tr>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_lovefactory&controller=payment&task=view&cid[]=' . $payment->id); ?>">
                                        <?php echo JHtml::date($payment->received_at, 'Y-m-d H:i:s'); ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_lovefactory&controller=user&task=view&id=' . $payment->user_id); ?>"><?php echo $payment->username; ?></a>
                                </td>
                                <td style="text-align: right; ">
                                    <?php echo JHtml::_('LoveFactory.currency', $payment->amount, $payment->currency); ?>
                                </td>
                                <td><?php echo $payment->status; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10"><?php echo JText::_('DASHBOARD_NO_PAYMENT_FOUND'); ?></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>

                    <tfoot>
                    <tr>
                        <td colspan="10" style="padding-top: 10px;"><a
                                href="<?php echo JRoute::_('index.php?option=com_lovefactory&task=payments'); ?>"><?php echo JText::_('DASHBOARD_ALL_PAYMENTS'); ?>
                        </td>
                    </tr>
                    </tfoot>
                </table>

            </fieldset>
        </td>

        <td width="50%">
            <fieldset>
                <legend><?php echo JText::_('DASHBOARD_LATEST_USERS'); ?></legend>

                <table class="adminlist">
                    <thead>
                    <tr>
                        <th style="width: 140px;"><?php echo JText::_('DASHBOARD_DATE'); ?></th>
                        <th><?php echo JText::_('DASHBOARD_USER'); ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (count($this->latest)): ?>
                        <?php foreach ($this->latest as $i => $user): ?>
                            <tr>
                                <td><?php echo JHtml::date($user->date, 'Y-m-d H:i:s'); ?></td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_lovefactory&controller=user&task=view&id=' . $user->user_id); ?>"><?php echo $user->username; ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10"><?php echo JText::_('DASHBOARD_NO_USER_FOUND'); ?></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>

                    <tfoot>
                    <tr>
                        <td colspan="10" style="padding-top: 10px;"><a
                                href="<?php echo JRoute::_('index.php?option=com_lovefactory&task=users'); ?>"><?php echo JText::_('DASHBOARD_ALL_USERS'); ?>
                        </td>
                    </tr>
                    </tfoot>
                </table>

            </fieldset>

            <fieldset>
                <legend><?php echo JText::_('DASHBOARD_LATEST_REPORTS'); ?></legend>

                <table class="adminlist">
                    <thead>
                    <tr>
                        <th style="width: 140px;"><?php echo JText::_('DASHBOARD_DATE'); ?></th>
                        <th><?php echo JText::_('DASHBAORD_USER_REPORTED'); ?></th>
                        <th width="18%"><?php echo JText::_('DASHBOARD_TYPE'); ?></th>
                        <th width="8%"
                        "><?php echo JText::_('DASHBOARD_STATUS'); ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (count($this->reports)): ?>
                        <?php foreach ($this->reports as $i => $report): ?>
                            <tr>
                                <td><?php echo $report->date; ?></td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_lovefactory&controller=user&task=view&id=' . $report->user_id); ?>"><?php echo $report->username; ?></a>
                                </td>
                                <td><?php echo $this->report_types[$report->type_id]; ?>
                                <td style="text-align: center;" class="jgrid">
                                    <span class="state <?php echo $report->status ? 'publish' : 'unpublish'; ?>"></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10"><?php echo JText::_('DASHBOARD_NO_REPORT_FOUND'); ?></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>

                    <tfoot>
                    <tr>
                        <td colspan="10" style="padding-top: 10px;"><a
                                href="<?php echo JRoute::_('index.php?option=com_lovefactory&task=reports'); ?>"><?php echo JText::_('DASHBOARD_ALL_REPORTS'); ?>
                        </td>
                    </tr>
                    </tfoot>
                </table>

            </fieldset>
        </td>
    </tr>
</table>
