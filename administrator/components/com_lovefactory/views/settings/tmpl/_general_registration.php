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
    <?php if ($this->joomlaUserRegistrationDisabledNotification): ?>
        <div class="alert alert-error">
            You must set <b>Allow User Registration</b> to <b>Yes</b> from Global Configuration / Users / Component tab
            in order for the registration process to function properly.
        </div>
    <?php endif; ?>

    <!-- registration_mode -->
    <tr class="hasTip"
        title="<?php echo JText::_('SETTINGS_REGISTRATION_MODE'); ?>::<?php echo JText::_('SETTINGS_REGISTRATION_MODE_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="registration_mode"><?php echo JText::_('SETTINGS_REGISTRATION_MODE'); ?></label>
      </span>
        </td>

        <td class="paramlist_value">
            <select id="registration_mode" name="registration_mode">
                <option
                    value="1" <?php echo (1 == $this->settings->registration_mode) ? 'selected="selected"' : ''; ?>><?php echo JText::_('SETTINGS_REGISTRATION_MODE_OVERRIDE'); ?></option>
                <option
                    value="2" <?php echo (2 == $this->settings->registration_mode) ? 'selected="selected"' : ''; ?>><?php echo JText::_('SETTINGS_REGISTRATION_MODE_JOOMLA'); ?></option>
            </select>
        </td>
    </tr>

    <!-- require_fillin -->
    <?php echo JHtml::_('settings.boolean', 'require_fillin', 'SETTINGS_REQUIRE_FILLIN', $this->settings->require_fillin, 'SETTINGS_REQUIRE_FILLIN_TIP'); ?>

    <!-- registration_membership -->
    <?php echo JHtml::_('settings.boolean', 'registration_membership', 'SETTINGS_REGISTRATION_REGISTRATION_MEMBERSHIP', $this->settings->registration_membership, 'SETTINGS_REGISTRATION_REGISTRATION_MEMBERSHIP_TIP'); ?>

    <!-- registration_login_redirect -->
    <tr class="hasTip"
        title="<?php echo JText::_('SETTINGS_REGISTRATION_LOGIN_REDIRECT'); ?>::<?php echo JText::_('SETTINGS_REGISTRATION_LOGIN_REDIRECT_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="registration_login_redirect"><?php echo JText::_('SETTINGS_REGISTRATION_LOGIN_REDIRECT'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <?php echo $this->redirect_items; ?>
        </td>
    </tr>

    <!-- registration_fields_mapping_username -->
    <tr class="hasTip"
        title="<?php echo JText::_('SETTINGS_REGISTRATION_FIELDS_MAPPING_USERNAME'); ?>::<?php echo JText::_('SETTINGS_REGISTRATION_FIELDS_MAPPING_USERNAME_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label
            for="registration_fields_mapping_username"><?php echo JText::_('SETTINGS_REGISTRATION_FIELDS_MAPPING_USERNAME'); ?></label>
      </span>
        </td>

        <td class="paramlist_value">
            <?php echo JHtml::_('select.genericlist', $this->fields_username, 'registration_fields_mapping_username', '', 'value', 'text', $this->settings->registration_fields_mapping_username); ?>
        </td>
    </tr>

    <!-- registration_fields_mapping_email -->
    <tr class="hasTip"
        title="<?php echo JText::_('SETTINGS_REGISTRATION_FIELDS_MAPPING_EMAIL'); ?>::<?php echo JText::_('SETTINGS_REGISTRATION_FIELDS_MAPPING_EMAIL_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label
            for="registration_fields_mapping_email"><?php echo JText::_('SETTINGS_REGISTRATION_FIELDS_MAPPING_EMAIL'); ?></label>
      </span>
        </td>

        <td class="paramlist_value">
            <?php echo JHtml::_('select.genericlist', $this->fields_email, 'registration_fields_mapping_email', '', 'value', 'text', $this->settings->registration_fields_mapping_email); ?>
        </td>
    </tr>

    <!-- registration_fields_mapping_password -->
    <tr class="hasTip"
        title="<?php echo JText::_('SETTINGS_REGISTRATION_FIELDS_MAPPING_PASSWORD'); ?>::<?php echo JText::_('SETTINGS_REGISTRATION_FIELDS_MAPPING_PASSWORD_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label
            for="registration_fields_mapping_password"><?php echo JText::_('SETTINGS_REGISTRATION_FIELDS_MAPPING_PASSWORD'); ?></label>
      </span>
        </td>

        <td class="paramlist_value">
            <?php echo JHtml::_('select.genericlist', $this->fields_password, 'registration_fields_mapping_password', '', 'value', 'text', $this->settings->registration_fields_mapping_password); ?>
        </td>
    </tr>

    <!-- registration_fields_mapping_name -->
    <tr class="hasTip"
        title="<?php echo JText::_('SETTINGS_REGISTRATION_FIELDS_MAPPING_NAME'); ?>::<?php echo JText::_('SETTINGS_REGISTRATION_FIELDS_MAPPING_NAME_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label
            for="registration_fields_mapping_name"><?php echo JText::_('SETTINGS_REGISTRATION_FIELDS_MAPPING_NAME'); ?></label>
      </span>
        </td>

        <td class="paramlist_value">
            <?php echo JHtml::_('select.genericlist', $this->fields_name, 'registration_fields_mapping_name', '', 'value', 'text', $this->settings->registration_fields_mapping_name); ?>
        </td>
    </tr>

    <tr>
        <td colspan="2"
            style="color: #ff0000; padding-bottom: 20px;"><?php echo JText::_('SETTINGS_REGISTRATION_FIELD_MAPPINGS_INFO'); ?></td>
    </tr>
</table>
