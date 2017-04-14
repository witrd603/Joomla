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
        <td width="40%" class="paramlist_key hasTooltip"
            title="<?php echo JText::_('SETTINGS_CREATE_PROFILE_ADMIN_GROUPS_TIP'); ?>">
      <span class="editlinktip">
        <label for="create_profile_admin_groups">
          <?php echo JText::_('SETTINGS_CREATE_PROFILE_ADMIN_GROUPS'); ?>
        </label>
      </span>
        </td>

        <td class="paramlist_value">
            <?php echo JHtml::_('access.usergroup', 'create_profile_admin_groups[]', $this->settings->create_profile_admin_groups, 'multiple="true" size="10"', false); ?>
        </td>
    </tr>
</table>
