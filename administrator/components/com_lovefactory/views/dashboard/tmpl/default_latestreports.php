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

<div class="box" id="portlet_latestreports">
    <div class="header2">
        <?php echo FactoryText::_('dashboard_box_latest_reports'); ?>
        <span>(<a
                href="<?php echo FactoryRoute::task('reports'); ?>"><?php echo FactoryText::_('dashboard_latest_media_view_all'); ?></a>)</span>
        <div style="display: none;" class="factory-icon icon-minus-circle minimize"></div>
    </div>
    <div class="content">

        <?php if ($this->latestReports): ?>
            <table>
                <thead>
                <tr>
                    <th><?php echo FactoryText::_('dashboard_latest_reportts_list_username'); ?></th>
                    <th width="80"><?php echo FactoryText::_('dashboard_latest_reports_list_type'); ?></th>
                    <th width="100"><?php echo FactoryText::_('dashboard_latest_reports_list_status'); ?></th>
                    <th width="140"><?php echo FactoryText::_('dashboard_latest_reports_list_date'); ?></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($this->latestReports as $this->i => $item): ?>
                    <tr class="<?php echo $this->i % 2 ? '' : 'even'; ?>">
                        <td>
                            <a href="<?php echo FactoryRoute::_('controller=report&task=edit&id=' . $item->id); ?>"><?php echo $item->username; ?></a>
                        </td>
                        <td><?php echo FactoryText::_('report_type_' . $item->element . ('' == $item->type ? '' : '_' . $item->type)); ?></td>
                        <td><?php echo JHtml::_('LoveFactoryAdministrator.reportLabel', $item->status); ?></td>
                        <td><?php echo JHtml::date($item->date, 'Y-m-d H:i:s'); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p><?php echo FactoryText::_('dashboard_list_no_items'); ?></p>
        <?php endif; ?>

    </div>
</div>
