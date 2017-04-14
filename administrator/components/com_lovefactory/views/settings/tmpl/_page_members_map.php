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
    <!-- enable_members_map -->
    <?php echo JHtml::_('settings.boolean', 'enable_members_map', 'SETTINGS_ENABLE_MEMBERS_MAP', $this->settings->enable_members_map); ?>

    <!-- members_map_gmap_field -->
    <tr class="members_map_related">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="members_map_gmap_field"><?php echo JText::_('SETTINGS_MEMBERS_MAP_GMAP_FIELD'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <?php echo JHtml::_('select.genericlist', $this->gmaps_fields, 'members_map_gmap_field', '', 'value', 'text', $this->settings->members_map_gmap_field); ?>
        </td>
    </tr>

    <!-- allow_guest_members_map -->
    <?php echo JHtml::_('settings.boolean', 'allow_guest_members_map', 'SETTINGS_ALLOW_GUEST_MEMBERS_MAP', $this->settings->allow_guest_members_map, null, null, null, 'members_map'); ?>

    <!-- members_map_group_users -->
    <?php echo JHtml::_('settings.boolean', 'members_map_group_users', 'SETTINGS_GROUP_MEMBERS', $this->settings->members_map_group_users, 'SETTINGS_GROUP_MEMBERS_TIP', null, null, 'members_map'); ?>

    <!-- members_map_group_zoom -->
    <tr class="members_map_related">
        <td width="40%" class="paramlist_key">
            <label
                for="members_map_group_zoom"><?php echo JText::_('SETTINGS_MEMBERSMAP_MAX_ZOOM_GROUP_USERS'); ?></label>
        </td>
        <td class="paramlist_value">
            <select id="members_map_group_zoom" name="members_map_group_zoom">
                <?php for ($i = 1; $i < 16; $i++): ?>
                    <option
                        value="<?php echo $i; ?>" <?php echo ($i == $this->settings->members_map_group_zoom) ? 'selected="selected"' : ''; ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </td>
    </tr>

    <!-- members_map_default_membership_show -->
    <?php echo JHtml::_('settings.boolean', 'members_map_default_membership_show', 'SETTINGS_GROUP_MEMBERS_DEFAULT_MEMBERSHIP', $this->settings->members_map_default_membership_show, 'SETTINGS_GROUP_MEMBERS_DEFAULT_MEMBERSHIP_TIP', null, null, 'members_map'); ?>

    <!-- members_map_profile_new_link -->
    <?php echo JHtml::_('settings.boolean', 'members_map_profile_new_link', 'SETTINGS_MEMBERS_MAP_PROFILE_NEW_LINK', $this->settings->members_map_profile_new_link, null, null, null, 'members_map'); ?>

    <!-- members_map_show_profile_event -->
    <tr class="members_map_show_profile_event">
        <td width="40%">
            <label
                for="members_map_show_profile_event"><?php echo JText::_('SETTINGS_MEMBERSMAP_SHOW_PROFILE_EVENT'); ?></label>
        </td>

        <td>
            <select id="members_map_show_profile_event" name="members_map_show_profile_event">
                <option
                    value="click" <?php echo ('click' == $this->settings->members_map_show_profile_event) ? 'selected="selected"' : ''; ?>>
                    <?php echo JText::_('SETTINGS_MEMBERSMAP_SHOW_PROFILE_EVENT_CLICK'); ?>
                </option>

                <option
                    value="mouseover" <?php echo ('mouseover' == $this->settings->members_map_show_profile_event) ? 'selected="selected"' : ''; ?>>
                    <?php echo JText::_('SETTINGS_MEMBERSMAP_SHOW_PROFILE_EVENT_MOUSEOVER'); ?>
                </option>
            </select>
        </td>
    </tr>

    <!-- members_map_grouped_members_display -->
    <tr class="members_map_grouped_members_display">
        <td width="40%">
            <label
                for="members_map_grouped_members_display"><?php echo JText::_('SETTINGS_MEMBERSMAP_GROUPED_MEMBERS_DISPLAY'); ?></label>
        </td>

        <td>
            <select id="members_map_grouped_members_display" name="members_map_grouped_members_display">
                <option
                    value="map" <?php echo ('map' == $this->settings->members_map_grouped_members_display) ? 'selected="selected"' : ''; ?>>
                    <?php echo JText::_('SETTINGS_MEMBERSMAP_GROUPED_MEMBERS_DISPLAY_MAP'); ?>
                </option>

                <option
                    value="page" <?php echo ('page' == $this->settings->members_map_grouped_members_display) ? 'selected="selected"' : ''; ?>>
                    <?php echo JText::_('SETTINGS_MEMBERSMAP_GROUPED_MEMBERS_DISPLAY_PAGE'); ?>
                </option>
            </select>
        </td>
    </tr>
</table>
