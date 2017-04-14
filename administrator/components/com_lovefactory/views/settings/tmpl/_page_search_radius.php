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
    <!-- enable_search_radius -->
    <?php echo JHtml::_('settings.boolean', 'enable_search_radius', 'SETTINGS_ENABLE_SEARCH_RADIUS', $this->settings->enable_search_radius); ?>

    <!-- search_radius_gmap_field -->
    <tr class="search_radius_related">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="search_radius_gmap_field"><?php echo JText::_('SETTINGS_RADIUS_SEARCH_GMAP_FIELD'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <?php echo JHtml::_('select.genericlist', $this->gmaps_fields, 'search_radius_gmap_field', '', 'value', 'text', $this->settings->search_radius_gmap_field); ?>
        </td>
    </tr>

    <!-- allow_guest_search_radius -->
    <?php echo JHtml::_('settings.boolean', 'allow_guest_search_radius', 'SETTINGS_ALLOW_GUESTS_RADIUS_SEARCH', $this->settings->allow_guest_search_radius, null, null, null, 'search_radius'); ?>

    <!-- max_search_radius -->
    <!--  <tr class="search_radius_related hasTip" title="-->
    <?php //echo JText::_('SETTINGS_MAX_SEARCH_RADIUS'); ?><!--::-->
    <?php //echo JText::_('SETTINGS_MAX_SEARCH_RADIUS_TIP'); ?><!--">-->
    <!--    <td width="40%" class="paramlist_key">-->
    <!--      <span class="editlinktip">-->
    <!--        <label for="max_search_radius">--><?php //echo JText::_('SETTINGS_MAX_SEARCH_RADIUS'); ?><!--</label>-->
    <!--      </span>-->
    <!--    </td>-->
    <!--    <td class="paramlist_value">-->
    <!--      <input type="text" name="max_search_radius" id="max_search_radius" value="-->
    <?php //echo $this->settings->max_search_radius; ?><!--" />-->
    <!--    </td>-->
    <!--  </tr>-->

    <!-- enable_search_radius_sex_filter -->
    <?php #echo JHtml::_('settings.boolean', 'enable_search_radius_sex_filter', 'SETTINGS_ENABLE_SEX_FITER', $this->settings->enable_search_radius_sex_filter, null, null, null, 'search_radius'); ?>

    <!-- search_radius_group_users -->
    <?php echo JHtml::_('settings.boolean', 'search_radius_group_users', 'SETTINGS_RADIUS_SEARCH_GROUP_MEMBERS', $this->settings->search_radius_group_users, 'SETTINGS_RADIUS_SEARCH_GROUP_MEMBERS_TIP', null, null, 'search_radius'); ?>

    <!-- search_radius_group_zoom -->
    <tr class="search_radius_related">
        <td width="40%" class="paramlist_key">
            <label
                for="search_radius_group_zoom"><?php echo JText::_('SETTINGS_RADIUS_MAX_ZOOM_GROUP_USERS'); ?></label>
        </td>
        <td class="paramlist_value">
            <select id="search_radius_group_zoom" name="search_radius_group_zoom">
                <?php for ($i = 1; $i < 16; $i++): ?>
                    <option
                        value="<?php echo $i; ?>" <?php echo ($i == $this->settings->search_radius_group_zoom) ? 'selected="selected"' : ''; ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </td>
    </tr>

    <!-- search_radius_default_membership_show -->
    <?php echo JHtml::_('settings.boolean', 'search_radius_default_membership_show', 'SETTINGS_SEARCH_RADIUS_DEFAULT_MEMBERSHIP', $this->settings->search_radius_default_membership_show, 'SETTINGS_SEARCH_RADIUS_DEFAULT_MEMBERSHIP_TIP', null, null, 'search_radius'); ?>

    <!-- search_radius_profile_new_link -->
    <?php echo JHtml::_('settings.boolean', 'search_radius_profile_new_link', 'SETTINGS_SEARCH_RADIUS_PROFILE_NEW_LINK', $this->settings->search_radius_profile_new_link, null, null, null, 'search_radius'); ?>
</table>
