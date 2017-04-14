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
    <!-- save_settings -->
    <tr>
        <td width="40%">
        <span class="editlinktip">
          <label for="save_settings"><?php echo JText::_('SETTINGS_BACKUP_INCLUDE_SETTINGS'); ?></label>
        </span>
        </td>

        <td class="paramlist_value">
            <select id="save_settings" name="save_settings">
                <option value="0"><?php echo JText::_('JNO'); ?></option>
                <option value="1" selected><?php echo JText::_('JYES'); ?></option>
            </select>
        </td>
    </tr>

    <tr>
        <td colspan="2" style="padding-top: 10px;">
            <input type="submit" value="<?php echo JText::_('SETTINGS_BACKUP_CREATE_BACKUP'); ?>"
                   onclick="submitbutton('backup');"/>
        </td>
    </tr>

    <tr>
        <td colspan="2" style="padding-top: 10px;">
            <h3><?php echo JText::_('SETTINGS_BACKUP_INFO'); ?></h3>
            <?php echo JText::sprintf('SETTINGS_BACKUP_INFO_TEXT', $this->app->getPhotosFolder()); ?>
        </td>
    </tr>
</table>
