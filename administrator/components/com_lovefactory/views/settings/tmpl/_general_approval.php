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
    <!-- approval_profile -->
    <?php echo JHtml::_('settings.boolean', 'approval_profile', 'SETTINGS_APPROVAL_PROFILE', $this->settings->approval_profile); ?>

    <!-- approval_photos -->
    <?php echo JHtml::_('settings.boolean', 'approval_photos', 'SETTINGS_APPROVAL_PHOTOS', $this->settings->approval_photos); ?>

    <!-- approval_videos -->
    <?php echo JHtml::_('settings.boolean', 'approval_videos', 'SETTINGS_APPROVAL_VIDEOS', $this->settings->approval_videos); ?>

    <!-- approval_comments -->
    <?php echo JHtml::_('settings.boolean', 'approval_comments', 'SETTINGS_APPROVAL_COMMENTS', $this->settings->approval_comments); ?>

    <!-- approval_comments_photo -->
    <?php echo JHtml::_('settings.boolean', 'approval_comments_photo', 'SETTINGS_APPROVAL_COMMENTS_PHOTO', $this->settings->approval_comments_photo); ?>

    <!-- approval_comments_video -->
    <?php echo JHtml::_('settings.boolean', 'approval_comments_video', 'SETTINGS_APPROVAL_COMMENTS_VIDEO', $this->settings->approval_comments_video); ?>

    <!-- approval_messages -->
    <?php echo JHtml::_('settings.boolean', 'approval_messages', 'SETTINGS_APPROVAL_MESSAGES', $this->settings->approval_messages); ?>

    <!-- approval_groups -->
    <?php echo JHtml::_('settings.boolean', 'approval_groups', 'SETTINGS_APPROVAL_GROUPS', $this->settings->approval_groups); ?>

    <!-- approval_group_threads -->
    <?php echo JHtml::_('settings.boolean', 'approval_group_threads', 'SETTINGS_APPROVAL_GROUPS_THREADS', $this->settings->approval_group_threads); ?>

    <!-- approval_groups_posts -->
    <?php echo JHtml::_('settings.boolean', 'approval_groups_posts', 'SETTINGS_APPROVAL_GROUPS_POST', $this->settings->approval_groups_posts); ?>
</table>
