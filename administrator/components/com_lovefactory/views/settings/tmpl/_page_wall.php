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

    <!-- enable_wallpage -->
    <?php echo JHtml::_('settings.boolean', 'enable_wallpage', 'SETTINGS_ENABLE_WALLPAGE', $this->settings->enable_wallpage); ?>

    <!-- wallpage_entries -->
    <tr class="wallpage_related" for="wallpage_entries">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="wallpage_entries"><?php echo JText::_('SETTINGS_WALLPAGE_ENTRIES'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="wallpage_entries" id="wallpage_entries"
                   value="<?php echo $this->settings->wallpage_entries; ?>"/>
        </td>
    </tr>

    <!-- wallpage_add_status -->
    <?php echo JHtml::_('settings.boolean', 'wallpage_add_status', 'SETTINGS_WALLPAGE_ADD_STATUS', $this->settings->wallpage_add_status, 'SETTINGS_WALLPAGE_ADD_STATUS_TIP', null, null, 'wallpage'); ?>

    <!-- wallpage_add_photo -->
    <?php echo JHtml::_('settings.boolean', 'wallpage_add_photo', 'SETTINGS_WALLPAGE_ADD_PHOTO', $this->settings->wallpage_add_photo, 'SETTINGS_WALLPAGE_ADD_PHOTO_TIP', null, null, 'wallpage'); ?>

    <!-- wallpage_add_rating -->
    <?php echo JHtml::_('settings.boolean', 'wallpage_add_rating', 'SETTINGS_WALLPAGE_ADD_RATING', $this->settings->wallpage_add_rating, 'SETTINGS_WALLPAGE_ADD_RATING_TIP', null, null, 'wallpage'); ?>

    <!-- wallpage_add_comment -->
    <?php echo JHtml::_('settings.boolean', 'wallpage_add_comment', 'SETTINGS_WALLPAGE_ADD_COMMENT', $this->settings->wallpage_add_comment, 'SETTINGS_WALLPAGE_ADD_COMMENT_TIP', null, null, 'wallpage'); ?>

    <!-- wallpage_add_photo_comment -->
    <?php echo JHtml::_('settings.boolean', 'wallpage_add_photo_comment', 'SETTINGS_WALLPAGE_ADD_PHOTO_COMMENT', $this->settings->wallpage_add_photo_comment, 'SETTINGS_WALLPAGE_ADD_PHOTO_COMMENT_TIP', null, null, 'wallpage'); ?>

    <!-- wallpage_add_video_comment -->
    <?php echo JHtml::_('settings.boolean', 'wallpage_add_video_comment', 'SETTINGS_WALLPAGE_ADD_VIDEO_COMMENT', $this->settings->wallpage_add_video_comment, 'SETTINGS_WALLPAGE_ADD_VIDEO_COMMENT_TIP', null, null, 'wallpage'); ?>

    <!-- wallpage_add_video -->
    <?php echo JHtml::_('settings.boolean', 'wallpage_add_video', 'SETTINGS_WALLPAGE_ADD_VIDEO', $this->settings->wallpage_add_video, 'SETTINGS_WALLPAGE_ADD_VIDEO_TIP', null, null, 'wallpage'); ?>

    <!-- wallpage_add_friend -->
    <?php echo JHtml::_('settings.boolean', 'wallpage_add_friend', 'SETTINGS_WALLPAGE_ADD_FRIEND', $this->settings->wallpage_add_friend, 'SETTINGS_WALLPAGE_ADD_FRIEND_TIP', null, null, 'wallpage'); ?>

    <!-- wallpage_add_relationship -->
    <?php echo JHtml::_('settings.boolean', 'wallpage_add_relationship', 'SETTINGS_WALLPAGE_ADD_RELATIONSHIP', $this->settings->wallpage_add_relationship, 'SETTINGS_WALLPAGE_ADD_relationship_tip', null, null, 'wallpage'); ?>

    <!-- wallpage_create_group -->
    <?php echo JHtml::_('settings.boolean', 'wallpage_create_group', 'SETTINGS_WALLPAGE_CREATE_GROUP', $this->settings->wallpage_create_group, 'SETTINGS_WALLPAGE_CREATE_GROUP_TIP', null, null, 'wallpage'); ?>

    <!-- wallpage_join_group -->
    <?php echo JHtml::_('settings.boolean', 'wallpage_join_group', 'SETTINGS_WALLPAGE_JOIN_GROUP', $this->settings->wallpage_join_group, 'SETTINGS_WALLPAGE_JOIN_GROUP_TIP', null, null, 'wallpage'); ?>
</table>
