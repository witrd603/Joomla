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
    <!-- bootstrap_template -->
    <?php echo JHtml::_('settings.boolean', 'bootstrap_template', 'SETTINGS_BOOTSTRAP_TEMPLATE', $this->settings->bootstrap_template, 'SETTINGS_BOOTSTRAP_TEMPLATE_TIP'); ?>

    <!-- display_hidden -->
    <?php echo JHtml::_(
        'settings.boolean',
        'display_hidden',
        'SETTINGS_SHOW_HIDDEN_FIELDS_STATUS',
        $this->settings->display_hidden,
        'SETTINGS_SHOW_HIDDEN_FIELDS_STATUS_TIP',
        array(
            0 => 'SETTINGS_SHOW_HIDDEN_FIELDS_HIDE',
            1 => 'SETTINGS_SHOW_HIDDEN_FIELDS_SHOW')); ?>

    <!-- profile_status_change -->
    <?php echo JHtml::_('settings.boolean', 'profile_status_change', 'SETTINGS_STATUS_CHANGE', $this->settings->profile_status_change, 'SETTINGS_STATUS_CHANGE_TIP'); ?>

    <!-- enable_friends -->
    <?php echo JHtml::_('settings.boolean', 'enable_friends', 'SETTINGS_ENABLE_FRIENDS', $this->settings->enable_friends); ?>

    <!-- enable_top_friends -->
    <?php echo JHtml::_('settings.boolean', 'enable_top_friends', 'SETTINGS_ENABLE_TOP_FRIENDS', $this->settings->enable_top_friends); ?>

    <!-- enable_relationships -->
    <?php echo JHtml::_('settings.boolean', 'enable_relationships', 'SETTINGS_ENABLE_RELATIONSHIPS', $this->settings->enable_relationships); ?>

    <!-- invalid_membership_action -->
    <?php echo JHtml::_(
        'settings.boolean',
        'invalid_membership_action',
        'SETTINGS_INVALID_MEMBERSHIP_ACTION',
        $this->settings->invalid_membership_action,
        null,
        array(
            0 => 'SETTINGS_INVALID_MEMBERSHIP_ACTION_ERROR',
            1 => 'SETTINGS_INVALID_MEMBERSHIP_ACTION_REDIRECT')); ?>

    <!-- delete_user_plugin -->
    <?php echo JHtml::_('settings.boolean', 'delete_user_plugin', 'SETTINGS_DELETE_PROFILE_WHEN_DELETE_FROM_JOOMLA', $this->settings->delete_user_plugin, 'SETTINGS_DELETE_PROFILE_WHEN_DELETE_FROM_JOOMLA_TIP'); ?>

    <!-- date_format -->
    <tr>
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="date_format"><?php echo JText::_('SETTINGS_DATE_FORMAT'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <?php echo JHtml::_('select.genericlist', array(
                'ago' => FactoryText::_('settings_date_format_option_ago'),
                'Y-m-d H:i:s' => JFactory::getDate()->format('Y-m-d H:i:s'),
                'Y-m-d H:i' => JFactory::getDate()->format('Y-m-d H:i'),
                'm/d/Y, H:i' => JFactory::getDate()->format('m/d/Y, H:i'),
                'd/m/Y, H:i' => JFactory::getDate()->format('d/m/Y, H:i'),
                'd M Y, H:i' => JFactory::getDate()->format('d M Y, H:i'),
                'M d Y, H:i' => JFactory::getDate()->format('M d Y, H:i'),
                'custom' => FactoryText::_('settings_date_format_option_custom'),
            ), 'date_format', '', '', '', $this->settings->date_format); ?>

            <span id="date_custom_format_wrapper" style="display: none;">
        <input id="date_custom_format" name="date_custom_format"
               value="<?php echo $this->settings->date_custom_format; ?>"/>
        <a href="http://php.net/manual/en/function.date.php"
           target="_blank"><?php echo JText::_('SETTINGS_DATE_FORMAT_INFO'); ?></a>
      </span>
        </td>
    </tr>

    <!-- admin_comments_delete -->
    <?php echo JHtml::_('settings.boolean', 'admin_comments_delete', 'SETTINGS_ADMINS_DELETE_COMMENTS', $this->settings->admin_comments_delete); ?>

    <!-- user_comments_delete -->
    <?php echo JHtml::_('settings.boolean', 'user_comments_delete', 'SETTINGS_USER_DELETE_COMMENTS', $this->settings->user_comments_delete); ?>

    <tr class="hasTip"
        title="<?php echo JText::_('SETTINGS_FRIENDSHIP_REQUESTS_LIMIT'); ?>::<?php echo JText::_('SETTINGS_FRIENDSHIP_REQUESTS_LIMIT_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="friendship_requests_limit"><?php echo JText::_('SETTINGS_FRIENDSHIP_REQUESTS_LIMIT'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" id="friendship_requests_limit" name="friendship_requests_limit"
                   value="<?php echo $this->settings->friendship_requests_limit; ?>"/>
        </td>
    </tr>

    <!-- friendship_request_message -->
    <?php echo JHtml::_('settings.boolean', 'friendship_request_message', 'SETTINGS_FRIENDSHIP_REQUEST_MESSAGE', $this->settings->friendship_request_message); ?>

    <!-- display_name -->
    <tr>
        <td width="40%" class="hasTooltip" title="<?php echo JText::_('SETTINGS_DISPLAY_NAME_TIP'); ?>">
            <label for="location_field_city"><?php echo JText::_('SETTINGS_DISPLAY_NAME'); ?></label>
        </td>

        <td>
            <?php echo JHtml::_('select.genericlist', $this->fields_name, 'display_user_name[0]', 'style="width: 108px;"', 'value', 'text', $this->settings->display_user_name[0]); ?>
            <?php echo JHtml::_('select.genericlist', $this->fields_name, 'display_user_name[1]', 'style="width: 108px;"', 'value', 'text', $this->settings->display_user_name[1]); ?>
        </td>
    </tr>

    <!-- opposite_gender_search -->
    <?php echo JHtml::_('settings.boolean', 'opposite_gender_search', 'SETTINGS_OPPOSITE_GENDER_SEARCH', $this->settings->opposite_gender_search, 'SETTINGS_OPPOSITE_GENDER_SEARCH_TIP'); ?>

    <!-- opposite_gender_display -->
    <?php echo JHtml::_('settings.boolean', 'opposite_gender_display', 'SETTINGS_OPPOSITE_GENDER_DISPLAY', $this->settings->opposite_gender_display, 'SETTINGS_OPPOSITE_GENDER_DISPLAY_TIP'); ?>

    <!-- restrict_default_membership -->
    <?php echo JHtml::_('settings.boolean', 'restrict_default_membership', 'SETTINGS_RESTRICT_DEFAULT_MEMBERSHIP', $this->settings->restrict_default_membership, 'SETTINGS_RESTRICT_DEFAULT_MEMBERSHIP_TIP'); ?>

    <!-- currency_symbol -->
    <?php echo JHtml::_(
        'settings.boolean',
        'currency_symbol',
        'SETTINGS_CURRENCY_SYMBOL',
        $this->settings->currency_symbol,
        'SETTINGS_CURRENCY_SYMBOL_TIP',
        array(
            0 => 'SETTINGS_CURRENCY_SYMBOL_BEFORE',
            1 => 'SETTINGS_CURRENCY_SYMBOL_AFTER')); ?>
</table>
