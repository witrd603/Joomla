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

<table class="paramlist admintable">
    <!-- cron_password -->
    <tr>
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="cron_password"><?php echo JText::_('SETTINGS_CRON_JOBS_PASSWORD'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="cron_password" id="cron_password"
                   value="<?php echo $this->settings->cron_password; ?>"/>
        </td>
    </tr>

    <!-- end_membership_notification -->
    <?php echo JHtml::_('settings.boolean', 'end_membership_notification', 'SETTINGS_CRON_SEND_END_MEMBERSHIP_NOTIFICATION', $this->settings->end_membership_notification, 'SETTINGS_CRON_SEND_END_MEMBERSHIP_NOTIFICATION_TIP'); ?>

    <!-- end_membership_notification_interval -->
    <tr class="hasTip"
        title="<?php echo JText::_('SETTINGS_END_MEMBERSHIP_NOTIFICATION_INTERVAL'); ?>::<?php echo JText::_('SETTINGS_END_MEMBERSHIP_NOTIFICATION_INTERVAL_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label
            for="end_membership_notify_interval"><?php echo JText::_('SETTINGS_END_MEMBERSHIP_NOTIFICATION_INTERVAL'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="end_membership_notify_interval" id="end_membership_notify_interval"
                   value="<?php echo $this->settings->end_membership_notify_interval; ?>"/>
        </td>
    </tr>

    <!-- cron_job_wallpage_entries_interval -->
    <tr class="hasTip"
        title="<?php echo JText::_('SETTINGS_DELETE_WALLPAGE_ENTRIES_INTERVAL'); ?>::<?php echo JText::_('SETTINGS_DELETE_WALLPAGE_ENTRIES_INTERVAL_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label
            for="cron_job_wallpage_entries_interval"><?php echo JText::_('SETTINGS_DELETE_WALLPAGE_ENTRIES_INTERVAL'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="cron_job_wallpage_entries_interval" id="cron_job_wallpage_entries_interval"
                   value="<?php echo $this->settings->cron_job_wallpage_entries_interval; ?>"/>
        </td>
    </tr>

    <!-- cron_job_shoutbox_messages -->
    <tr class="hasTip"
        title="<?php echo JText::_('SETTINGS_DELETE_SHOUTBOX_MESSAGES_INTERVAL'); ?>::<?php echo JText::_('SETTINGS_DELETE_SHOUTBOX_MESSAGES_INTERVAL_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label
            for="cron_job_shoutbox_messages"><?php echo JText::_('SETTINGS_DELETE_SHOUTBOX_MESSAGES_INTERVAL'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="cron_job_shoutbox_messages" id="cron_job_shoutbox_messages"
                   value="<?php echo $this->settings->cron_job_shoutbox_messages; ?>"/>
        </td>
    </tr>

    <!-- cron_job_profile_visitors -->
    <tr class="hasTip"
        title="<?php echo JText::_('SETTINGS_DELETE_PORFILE_VISITORS_INTERVAL'); ?>::<?php echo JText::_('SETTINGS_DELETE_PORFILE_VISITORS_INTERVAL_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label
            for="cron_job_profile_visitors"><?php echo JText::_('SETTINGS_DELETE_PORFILE_VISITORS_INTERVAL'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="cron_job_profile_visitors" id="cron_job_profile_visitors"
                   value="<?php echo $this->settings->cron_job_profile_visitors; ?>"/>
        </td>
    </tr>

    <!-- cron_link -->
    <tr class="hasTip"
        title="<?php echo JText::_('SETTINGS_CRON_JOBS_LINK'); ?>::<?php echo JText::_('SETTINGS_CRON_JOBS_LINK_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="cron_link"><?php echo JText::_('SETTINGS_CRON_JOBS_LINK'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <?php echo JText::sprintf('SETTINGS_CRON_JOBS_INFO', JUri::root()); ?>
        </td>
    </tr>

    <tr>
        <td colspan="2">
            <a href="index.php?option=com_lovefactory&controller=settings&task=log&format=raw" target="_blank"
               class="btn btn-small btn-primary">View Cron Job log
                (<?php echo JHtml::_('number.bytes', @filesize(JPATH_ADMINISTRATOR . '/components/com_lovefactory/cron_log.php')); ?>
                )</a>
            <a href="index.php?option=com_lovefactory&controller=settings&task=clearlog"
               class="btn btn-small btn-danger">Clear log</a>
        </td>
    </tr>

</table>
