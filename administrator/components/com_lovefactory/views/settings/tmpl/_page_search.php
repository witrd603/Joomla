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
    <!-- number_search_results_per_page -->
    <tr>
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="number_search_results_per_page">
          <?php echo JText::_('SETTINGS_NUM_SEARCH_RESULTS_PER_PAGE'); ?>
        </label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="number_search_results_per_page" id="number_search_results_per_page"
                   value="<?php echo $this->settings->number_search_results_per_page; ?>"/>
        </td>
    </tr>

    <!-- limit_search_results -->
    <tr class="hasTip"
        title="<?php echo JText::_('SETTINGS_LIMIT_SEARCH_RESULTS_TO'); ?>::<?php echo JText::_('SETTINGS_LIMIT_SEARCH_RESULTS_TO_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="limit_search_results">
          <?php echo JText::_('SETTINGS_LIMIT_SEARCH_RESULTS_TO'); ?>
        </label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="limit_search_results" id="limit_search_results"
                   value="<?php echo $this->settings->limit_search_results; ?>"/>
        </td>
    </tr>

    <!-- hide_banned_profiles -->
    <?php echo JHtml::_('settings.boolean', 'hide_banned_profiles', 'SETTINGS_HIDE_BANNED_PROFILES', $this->settings->hide_banned_profiles); ?>

    <!-- hide_ignored_profiles -->
    <?php echo JHtml::_('settings.boolean', 'hide_ignored_profiles', 'SETTINGS_HIDE_IGNORED_PROFILES', $this->settings->hide_ignored_profiles); ?>

    <!-- sort_by_membership -->
    <?php echo JHtml::_('settings.boolean', 'sort_by_membership', 'SETTINGS_SORT_BY_MEMBERSHIP', $this->settings->sort_by_membership, 'SETTINGS_SORT_BY_MEMBERSHIP_TIP'); ?>

    <!-- results_default_sort_by -->
    <tr>
        <td width="40%" class="paramlist_key">
            <label for="results_default_sort_by"><?php echo JText::_('SETTINGS_DEFAULT_SORT_RESULTS_BY'); ?></label>
        </td>
        <td class="paramlist_value">
            <select id="results_default_sort_by" name="results_default_sort_by">
                <option
                    value="1" <?php echo (1 == $this->settings->results_default_sort_by) ? 'selected="selected"' : ''; ?>><?php echo JText::_('SETTINGS_DEFAULT_SORT_RESULTS_BY_USERNAME'); ?></option>
                <!--        <option value="2" -->
                <?php //echo (2 == $this->settings->results_default_sort_by) ? 'selected="selected"' : ''; ?><!-->-->
                <?php //echo JText::_('SETTINGS_DEFAULT_SORT_RESULTS_BY_FRIENDS'); ?><!--</option>-->
                <!--        <option value="3" -->
                <?php //echo (3 == $this->settings->results_default_sort_by) ? 'selected="selected"' : ''; ?><!-->-->
                <?php //echo JText::_('SETTINGS_DEFAULT_SORT_RESULTS_BY_PHOTOS'); ?><!--</option>-->
                <option
                    value="4" <?php echo (4 == $this->settings->results_default_sort_by) ? 'selected="selected"' : ''; ?>><?php echo JText::_('SETTINGS_DEFAULT_SORT_RESULTS_BY_PROFILE_RATING'); ?></option>
                <option
                    value="5" <?php echo (5 == $this->settings->results_default_sort_by) ? 'selected="selected"' : ''; ?>><?php echo JText::_('SETTINGS_DEFAULT_SORT_RESULTS_BY_LAST_SEEN'); ?></option>
            </select>
        </td>
    </tr>

    <!-- results_default_sort_order -->
    <?php echo JHtml::_(
        'settings.boolean',
        'results_default_sort_order',
        'SETTINGS_DEFAULT_SORT_RESULTS',
        $this->settings->results_default_sort_order,
        null,
        array(
            1 => 'ASC',
            0 => 'DESC',
        )
    ); ?>

    <!-- results_columns -->
    <tr class="hasTip"
        title="<?php echo JText::_('SETTINGS_SHOW_RESULTS_IN_COLUMNS'); ?>::<?php echo JText::_('SETTINGS_SHOW_RESULTS_IN_COLUMNS_TIP'); ?>">
        <td width="40%" class="paramlist_key">
            <label for="results_columns"><?php echo JText::_('SETTINGS_SHOW_RESULTS_IN_COLUMNS'); ?></label>
        </td>
        <td class="paramlist_value">
            <select id="results_columns" name="results_columns">
                <option
                    value="1" <?php echo (1 == $this->settings->results_columns) ? 'selected="selected"' : ''; ?>><?php echo JText::sprintf('SETTINGS_SHOW_RESULTS_IN_COLUMNS_COLUMN', 1); ?></option>
                <option
                    value="2" <?php echo (2 == $this->settings->results_columns) ? 'selected="selected"' : ''; ?>><?php echo JText::sprintf('SETTINGS_SHOW_RESULTS_IN_COLUMNS_COLUMNS', 2); ?></option>
            </select>
        </td>
    </tr>

    <!-- search_default_membership_show -->
    <?php echo JHtml::_('settings.boolean', 'search_default_membership_show', 'SETTINGS_SEARCH_RADIUS_DEFAULT_MEMBERSHIP', $this->settings->search_default_membership_show, 'SETTINGS_SEARCH_RADIUS_DEFAULT_MEMBERSHIP_TIP'); ?>

    <!-- profile_link_new_window -->
    <?php echo JHtml::_('settings.boolean', 'profile_link_new_window', 'SETTINGS_PROFILE_LINK_NEW_WINDOW', $this->settings->profile_link_new_window); ?>

    <!-- search_jump_to_results -->
    <?php echo JHtml::_('settings.boolean', 'search_jump_to_results', 'SETTINGS_SEARCH_JUMP_TO_RESULTS', $this->settings->search_jump_to_results); ?>
</table>
