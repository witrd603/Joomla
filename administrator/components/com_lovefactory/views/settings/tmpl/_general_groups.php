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
    <!-- enable_groups -->
    <?php echo JHtml::_('settings.boolean', 'enable_groups', 'SETTINGS_ENABLE_GROUPS', $this->settings->enable_groups); ?>

    <!-- groups_allow_users_create -->
    <?php echo JHtml::_('settings.boolean', 'groups_allow_users_create', 'SETTINGS_ALLOW_USERS_CREATE_GROUP', $this->settings->groups_allow_users_create, null, null, null, 'groups'); ?>

    <!-- groups_post_allowed_html -->
    <tr class="hasTip groups_related"
        title="<?php echo JText::_('SETTINGS_GROUPS_ALLOWED_HTML'); ?>::<?php echo JText::_('SETTINGS_GROUPS_ALLOWED_HTML_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="groups_post_allowed_html"><?php echo JText::_('SETTINGS_GROUPS_ALLOWED_HTML'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" id="groups_post_allowed_html" name="groups_post_allowed_html"
                   value="<?php echo $this->settings->groups_post_allowed_html; ?>" style="width: 200px;"/>
        </td>
    </tr>

    <!-- groups_photo_max_width -->
    <!--  <tr class="hasTip groups_related" title="-->
    <?php //echo JText::_('SETTINGS_GROUPS_PHOTO_MAX_WIDTH'); ?><!--::-->
    <?php //echo JText::_('SETTINGS_GROUPS_PHOTO_MAX_WIDTH_TIP'); ?><!--">-->
    <!--    <td width="40%" class="paramlist_key">-->
    <!--      <span class="editlinktip">-->
    <!--        <label for="groups_photo_max_width">-->
    <?php //echo JText::_('SETTINGS_GROUPS_PHOTO_MAX_WIDTH'); ?><!--</label>-->
    <!--      </span>-->
    <!--    </td>-->
    <!--    <td class="paramlist_value">-->
    <!--      <input type="text" id="groups_photo_max_width" name="groups_photo_max_width" value="-->
    <?php //echo $this->settings->groups_photo_max_width; ?><!--" />-->
    <!--    </td>-->
    <!--  </tr>-->

    <!-- groups_photo_max_height -->
    <!--  <tr class="hasTip groups_related" title="-->
    <?php //echo JText::_('SETTINGS_GROUPS_PHOTO_MAX_HEIGHT'); ?><!--::-->
    <?php //echo JText::_('SETTINGS_GROUPS_PHOTO_MAX_HEIGHT_TIP'); ?><!--">-->
    <!--    <td height="40%" class="paramlist_key">-->
    <!--      <span class="editlinktip">-->
    <!--        <label for="groups_photo_max_height">-->
    <?php //echo JText::_('SETTINGS_GROUPS_PHOTO_MAX_HEIGHT'); ?><!--</label>-->
    <!--      </span>-->
    <!--    </td>-->
    <!--    <td class="paramlist_value">-->
    <!--      <input type="text" id="groups_photo_max_height" name="groups_photo_max_height" value="-->
    <?php //echo $this->settings->groups_photo_max_height; ?><!--" />-->
    <!--    </td>-->
    <!--  </tr>-->

    <!-- groups_list_limit -->
    <tr class="hasTip groups_related"
        title="<?php echo JText::_('SETTINGS_GROUPS_LIST_LIMIT'); ?>::<?php echo JText::_('SETTINGS_GROUPS_LIST_LIMIT_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="groups_list_limit"><?php echo JText::_('SETTINGS_GROUPS_LIST_LIMIT'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" id="groups_list_limit" name="groups_list_limit"
                   value="<?php echo $this->settings->groups_list_limit; ?>" style="width: 200px;"/>
        </td>
    </tr>

    <!-- group_posts_list_limit -->
    <tr class="hasTip groups_related"
        title="<?php echo JText::_('SETTINGS_GROUP_POST_LIST_LIMIT'); ?>::<?php echo JText::_('SETTINGS_GROUP_POST_LIST_LIMIT'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="group_posts_list_limit"><?php echo JText::_('SETTINGS_GROUP_POST_LIST_LIMIT'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" id="group_posts_list_limit" name="group_posts_list_limit"
                   value="<?php echo $this->settings->group_posts_list_limit; ?>" style="width: 200px;"/>
        </td>
    </tr>

</table>
