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
    <!-- backup_file -->
    <tr>
        <td width="40%" class="paramlist_key">
        <span class="editlinktip">
          <label for="backup_file"><?php echo JText::_('SETTINGS_RESTORE_BACKUP_FILE'); ?></label>
        </span>
        </td>
        <td class="paramlist_value"><input type="file" id="backup_file" name="backup_file"/></td>
    </tr>

    <!-- restore_joomla_users -->
    <?php #echo JHtml::_('settings.boolean', 'restore_joomla_users', 'SETTINGS_RESTORE_JOOMLA_USERS', 0); ?>

    <tr>
        <td colspan="2">
            <h1 style="color: #ff0000;"><?php echo JText::_('SETTINGS_RESTORE_WARNING'); ?></h1>
            <?php echo JText::_('SETTINGS_RESTORE_WARNING_TEXT'); ?>
        </td>
    </tr>

    <tr>
        <td colspan="2" style="padding-top: 10px;">
            <input type="submit" value="<?php echo JText::_('SETTINGS_RESTORE_RESTORE_BACKUP'); ?>"
                   onclick="submitbutton('restore');"/>
        </td>
    </tr>
</table>
