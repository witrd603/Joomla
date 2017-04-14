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
    <!-- notification_change_membership_enabled -->
    <?php echo JHtml::_('settings.boolean', 'notification_change_membership_enabled', 'SETTINGS_ENABLE_NOTIFICATION', $this->settings->notification_change_membership_enabled); ?>

    <!-- notification_change_membership_receivers -->
    <tr>
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label class="hasTip" for="notification_change_membership_receivers"
               title="<?php echo JText::_('Receivers'); ?>::<?php echo JText::_('Receivers'); ?>"><?php echo JText::_('Receivers'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <select id="notification_change_membership_receivers" name="notification_change_membership_receivers[]"
                    multiple>
                <?php foreach ($this->admins as $admin): ?>
                    <option
                        value="<?php echo $admin->id; ?>" <?php echo in_array($admin->id, $this->settings->notification_change_membership_receivers) ? 'selected' : ''; ?>><?php echo $admin->username; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
</table>
