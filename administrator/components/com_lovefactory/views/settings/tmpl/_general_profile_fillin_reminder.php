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
    <!-- profile_fillin_reminder_enable -->
    <?php echo JHtml::_('settings.boolean', 'profile_fillin_reminder_enable', 'SETTINGS_PROFILE_FILLIN_REMINDER_ENABLE', $this->settings->profile_fillin_reminder_enable); ?>

    <!-- profile_fillin_reminder_interval -->
    <tr>
        <td width="40%" class="paramlist_key hasTooltip"
            title="<?php echo JText::_('SETTINGS_PROFILE_FILLIN_REMINDER_INTERVAL_TIP'); ?>">
      <span class="editlinktip">
        <label for="photos_max_size"><?php echo JText::_('SETTINGS_PROFILE_FILLIN_REMINDER_INTERVAL'); ?></label>
      </span>
        </td>

        <td class="paramlist_value">
            <input type="text" name="profile_fillin_reminder_interval" id="profile_fillin_reminder_interval"
                   value="<?php echo $this->settings->profile_fillin_reminder_interval; ?>"/>
        </td>
    </tr>

    <tr>
        <td colspan="2"
            style="color: #999999; padding-bottom: 20px;"><?php echo JText::sprintf('SETTINGS_PROFILE_FILLIN_REMINDER_INFO_NOTIFICATION', JText::_('COM_LOVEFACTORY_NOTIFICATION_PROFILE_FILLIN_REMINDER')); ?></td>
    </tr>
</table>
