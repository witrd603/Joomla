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
    <!-- enable_status -->
    <?php echo JHtml::_('settings.boolean', 'enable_status', 'SETTINGS_ENABLE_STATUS', $this->settings->enable_status); ?>

    <!-- status_max_length -->
    <tr class="status_related">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="status_max_length"><?php echo JText::_('SETTINGS_STATUS_MAX_LENGTH'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="status_max_length" id="status_max_length"
                   value="<?php echo $this->settings->status_max_length; ?>"/>
        </td>
    </tr>

    <!-- enable_comments -->
    <?php echo JHtml::_('settings.boolean', 'enable_comments', 'SETTINGS_ENABLE_USER_COMMENTS', $this->settings->enable_comments); ?>

    <!-- enable_messages -->
    <?php echo JHtml::_('settings.boolean', 'enable_messages', 'SETTINGS_ENABLE_USER_MESSAGES', $this->settings->enable_messages); ?>

    <!-- enable_rating -->
    <?php echo JHtml::_('settings.boolean', 'enable_rating', 'SETTINGS_ENABLE_USER_RATING', $this->settings->enable_rating); ?>

    <!-- enable_rating_update -->
    <?php echo JHtml::_('settings.boolean', 'enable_rating_update', 'SETTINGS_ENABLE_USER_RATING_UPDATE', $this->settings->enable_rating_update, 'SETTINGS_ENABLE_USER_RATING_UPDATE_TIP'); ?>

    <!-- remove_ratings_on_profile_remove -->
    <?php echo JHtml::_('settings.boolean', 'remove_ratings_on_profile_remove', 'SETTINGS_REMOVE_RATINGS_ON_PROFILE_REMOVE', $this->settings->remove_ratings_on_profile_remove, 'SETTINGS_REMOVE_RATINGS_ON_PROFILE_REMOVE_TIP', array(), null, 'rating'); ?>

    <!-- allow_guests_view_profile -->
    <?php echo JHtml::_('settings.boolean', 'allow_guests_view_profile', 'SETTINGS_ALLOW_GUESTS_VIEW_PROFILES', $this->settings->allow_guests_view_profile); ?>
</table>
