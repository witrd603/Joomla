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
    <!-- default_photo_extension -->
    <tr class="hasTip"
        title="<?php echo JText::_('SETTINGS_EXTENSION_DEFAULT_PHOTOS'); ?>::<?php echo JText::_('SETTINGS_EXTENSION_DEFAULT_PHOTOS_TIP'); ?>">
        <td width="40%" class="paramlist_key">
            <label for="default_photo_extension"><?php echo JText::_('SETTINGS_EXTENSION_DEFAULT_PHOTOS'); ?></label>
        </td>
        <td class="paramlist_value">
            <input id="default_photo_extension" type="text" name="default_photo_extension"
                   value="<?php echo $this->settings->default_photo_extension; ?>"/>
        </td>
    </tr>

    <!-- photo_max_width -->
    <tr>
        <td width="40%" class="paramlist_key hasTip"
            title="<?php echo JText::_('SETTINGS_PHOTO_MAX_WIDTH'); ?>::<?php echo JText::_('SETTINGS_PHOTO_MAX_WIDTH_TIP'); ?>">
      <span class="editlinktip">
        <label for="photo_max_width"><?php echo JText::_('SETTINGS_PHOTO_MAX_WIDTH'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="photo_max_width" id="photo_max_width"
                   value="<?php echo $this->settings->photo_max_width; ?>"/>
        </td>
    </tr>

    <!-- photo_max_height -->
    <tr>
        <td height="40%" class="paramlist_key hasTip"
            title="<?php echo JText::_('SETTINGS_PHOTO_MAX_HEIGHT'); ?>::<?php echo JText::_('SETTINGS_PHOTO_MAX_HEIGHT_TIP'); ?>">
      <span class="editlinktip">
        <label for="photo_max_height"><?php echo JText::_('SETTINGS_PHOTO_MAX_HEIGHT'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="photo_max_height" id="photo_max_height"
                   value="<?php echo $this->settings->photo_max_height; ?>"/>
        </td>
    </tr>

    <!-- thumbnail_max_width -->
    <tr>
        <td width="40%" class="paramlist_key hasTip"
            title="<?php echo JText::_('SETTINGS_THUMB_MAX_WIDTH'); ?>::<?php echo JText::_('SETTINGS_THUMB_MAX_WIDTH_TIP'); ?>">
      <span class="editlinktip">
        <label for="thumbnail_max_width"><?php echo JText::_('SETTINGS_THUMB_MAX_WIDTH'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="thumbnail_max_width" id="thumbnail_max_width"
                   value="<?php echo $this->settings->thumbnail_max_width; ?>"/>
        </td>
    </tr>

    <!-- thumbnail_max_height -->
    <tr>
        <td height="40%" class="paramlist_key hasTip"
            title="<?php echo JText::_('SETTINGS_THUMB_MAX_HEIGHT'); ?>::<?php echo JText::_('SETTINGS_THUMB_MAX_HEIGHT_TIP'); ?>">
      <span class="editlinktip">
        <label for="thumbnail_max_height"><?php echo JText::_('SETTINGS_THUMB_MAX_HEIGHT'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="thumbnail_max_height" id="thumbnail_max_height"
                   value="<?php echo $this->settings->thumbnail_max_height; ?>"/>
        </td>
    </tr>

    <tr>
        <td width="40%" class="paramlist_key hasTip"
            title="<?php echo JText::_('SETTINGS_PHOTOS_MAX_SIZE'); ?>::<?php echo JText::_('SETTINGS_PHOTOS_MAX_SIZE_TIP'); ?>">
      <span class="editlinktip">
        <label for="photos_max_size"><?php echo JText::_('SETTINGS_PHOTOS_MAX_SIZE'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="photos_max_size" id="photos_max_size"
                   value="<?php echo $this->settings->photos_max_size; ?>"/>
        </td>
    </tr>

    <!-- photos_storage_mode -->
    <tr class="hasTip"
        title="<?php echo JText::_('SETTINGS_PHOTOS_STORAGE_MODE'); ?>::<?php echo JText::_('SETTINGS_PHOTOS_STORAGE_MODE_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="photos_storage_mode"><?php echo JText::_('SETTINGS_PHOTOS_STORAGE_MODE'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <select id="photos_storage_mode" name="photos_storage_mode">
                <option
                    value="1" <?php echo (1 == $this->settings->photos_storage_mode) ? 'selected="selected"' : ''; ?>><?php echo JText::_('SETTINGS_PHOTOS_STORAGE_MODE_SEPARATE_FOLDER'); ?></option>
                <option
                    value="2" <?php echo (2 == $this->settings->photos_storage_mode) ? 'selected="selected"' : ''; ?>><?php echo JText::_('SETTINGS_PHOTOS_STORAGE_MODE_SAME_FOLDER'); ?></option>
            </select>
        </td>
    </tr>
</table>
