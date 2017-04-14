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

<form action="index.php" method="post" name="adminForm" id="adminForm">

    <fieldset>
        <div class="fltrt">
            <button type="button" onclick="Joomla.submitform('save', this.form);">
                <?php echo JText::_('JSAVE'); ?></button>
            <button type="button" onclick="Joomla.submitform('apply', this.form);">
                <?php echo JText::_('JAPPLY'); ?></button>
            <button type="button" onclick="window.parent.SqueezeBox.close();">
                <?php echo JText::_('JCANCEL'); ?></button>
        </div>
        <div class="configuration">
            <?php echo JText::_('USERS_OPTIONS') ?>
        </div>
    </fieldset>

    <table class="paramlist admintable">
        <tr class="hasTip">
            <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="default_membership_access"><?php echo JText::_('USERS_WHEN_DELETING_USER'); ?></label>
      </span>
            </td>

            <td class="paramlist_value">
                <table class="paramlist admintable">
                    <tr>
                        <td width="40%" class="paramlist_key">
            <span class="editlinktip">
              <label for="user_delete_comments"><?php echo JText::_('USER_DELETE_PROFILE_COMMENTS'); ?></label>
            </span>
                        </td>
                        <td class="paramlist_value">
                            <select id="user_delete_comments" name="user_delete_comments">
                                <option
                                    value="0" <?php echo (0 == $this->settings->user_delete_comments) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JNO'); ?></option>
                                <option
                                    value="1" <?php echo (1 == $this->settings->user_delete_comments) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JYES'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td width="40%" class="paramlist_key">
            <span class="editlinktip">
              <label for="user_delete_photo_comments"><?php echo JText::_('USER_DELETE_PHOTO_COMMENTS'); ?></label>
            </span>
                        </td>
                        <td class="paramlist_value">
                            <select id="user_delete_photo_comments" name="user_delete_photo_comments">
                                <option
                                    value="0" <?php echo (0 == $this->settings->user_delete_photo_comments) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JNO'); ?></option>
                                <option
                                    value="1" <?php echo (1 == $this->settings->user_delete_photo_comments) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JYES'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td width="40%" class="paramlist_key">
            <span class="editlinktip">
              <label for="user_delete_video_comments"><?php echo JText::_('USER_DELETE_VIDEO_COMMENTS'); ?></label>
            </span>
                        </td>
                        <td class="paramlist_value">
                            <select id="user_delete_video_comments" name="user_delete_video_comments">
                                <option
                                    value="0" <?php echo (0 == $this->settings->user_delete_video_comments) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JNO'); ?></option>
                                <option
                                    value="1" <?php echo (1 == $this->settings->user_delete_video_comments) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JYES'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td width="40%" class="paramlist_key">
            <span class="editlinktip">
              <label
                  for="user_delete_profile_visits"><?php echo JText::_('USER_DELETE_VISISTS_TO_OTHER_PROFILES'); ?></label>
            </span>
                        </td>
                        <td class="paramlist_value">
                            <select id="user_delete_profile_visits" name="user_delete_profile_visits">
                                <option
                                    value="0" <?php echo (0 == $this->settings->user_delete_profile_visits) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JNO'); ?></option>
                                <option
                                    value="1" <?php echo (1 == $this->settings->user_delete_profile_visits) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JYES'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td width="40%" class="paramlist_key">
            <span class="editlinktip">
              <label for="user_delete_ratings"><?php echo JText::_('USER_DELETE_RATINGS'); ?></label>
            </span>
                        </td>
                        <td class="paramlist_value">
                            <select id="user_delete_ratings" name="user_delete_ratings">
                                <option
                                    value="0" <?php echo (0 == $this->settings->user_delete_ratings) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JNO'); ?></option>
                                <option
                                    value="1" <?php echo (1 == $this->settings->user_delete_ratings) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JYES'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td width="40%" class="paramlist_key">
            <span class="editlinktip">
              <label for="user_delete_shoutbox"><?php echo JText::_('USER_DELETE_SHOUTBOX_ENTRIES'); ?></label>
            </span>
                        </td>
                        <td class="paramlist_value">
                            <select id="user_delete_shoutbox" name="user_delete_shoutbox">
                                <option
                                    value="0" <?php echo (0 == $this->settings->user_delete_shoutbox) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JNO'); ?></option>
                                <option
                                    value="1" <?php echo (1 == $this->settings->user_delete_shoutbox) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JYES'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td width="40%" class="paramlist_key">
            <span class="editlinktip">
              <label for="user_delete_interactions"><?php echo JText::_('USER_DELETE_INTERACTIONS'); ?></label>
            </span>
                        </td>
                        <td class="paramlist_value">
                            <select id="user_delete_interactions" name="user_delete_interactions">
                                <option
                                    value="0" <?php echo (0 == $this->settings->user_delete_interactions) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JNO'); ?></option>
                                <option
                                    value="1" <?php echo (1 == $this->settings->user_delete_interactions) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JYES'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td width="40%" class="paramlist_key">
            <span class="editlinktip">
              <label for="user_delete_payments"><?php echo JText::_('USER_DELETE_PAYMENTS'); ?></label>
            </span>
                        </td>
                        <td class="paramlist_value">
                            <select id="user_delete_payments" name="user_delete_payments">
                                <option
                                    value="0" <?php echo (0 == $this->settings->user_delete_payments) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JNO'); ?></option>
                                <option
                                    value="1" <?php echo (1 == $this->settings->user_delete_payments) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JYES'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td width="40%" class="paramlist_key">
            <span class="editlinktip">
              <label for="user_delete_actions"><?php echo JText::_('USER_DELETE_WALLPAGE_ENTRIES'); ?></label>
            </span>
                        </td>
                        <td class="paramlist_value">
                            <select id="user_delete_actions" name="user_delete_actions">
                                <option
                                    value="0" <?php echo (0 == $this->settings->user_delete_actions) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JNO'); ?></option>
                                <option
                                    value="1" <?php echo (1 == $this->settings->user_delete_actions) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JYES'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td width="40%" class="paramlist_key">
            <span class="editlinktip">
              <label for="user_delete_created_groups"><?php echo JText::_('USER_DELETE_CREATED_GROUPS'); ?></label>
            </span>
                        </td>
                        <td class="paramlist_value">
                            <select id="user_delete_created_groups" name="user_delete_created_groups">
                                <option
                                    value="0" <?php echo (0 == $this->settings->user_delete_created_groups) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JNO'); ?></option>
                                <option
                                    value="1" <?php echo (1 == $this->settings->user_delete_created_groups) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JYES'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td width="40%" class="paramlist_key">
            <span class="editlinktip">
              <label for="user_delete_posts_in_groups"><?php echo JText::_('USER_DELETE_POSTS_IN_GROUPS'); ?></label>
            </span>
                        </td>
                        <td class="paramlist_value">
                            <select id="user_delete_posts_in_groups" name="user_delete_posts_in_groups">
                                <option
                                    value="0" <?php echo (0 == $this->settings->user_delete_posts_in_groups) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JNO'); ?></option>
                                <option
                                    value="1" <?php echo (1 == $this->settings->user_delete_posts_in_groups) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JYES'); ?></option>
                            </select>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

    <input type="hidden" name="controller" value="settings"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value="save"/>
    <input type="hidden" name="modal" value="1"/>
</form>

<style>
    td {
        vertical-align: top;
    }
</style>
