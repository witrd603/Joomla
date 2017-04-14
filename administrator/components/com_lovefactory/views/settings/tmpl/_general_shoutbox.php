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
    <!-- enable_shoutbox -->
    <?php echo JHtml::_('settings.boolean', 'enable_shoutbox', 'SETTINGS_ENABLE_SHOUTBOX', $this->settings->enable_shoutbox, 'SETTINGS_ENABLE_SHOUTBOX_TIP'); ?>

    <!-- shoutbox_refresh_interval -->
    <tr class="shoutbox_related hasTip"
        title="<?php echo JText::_('SETTINGS_SHOUTBOX_REFRESH_INTERVAL'); ?>::<?php echo JText::_('SETTINGS_SHOUTBOX_REFRESH_INTERVAL_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="shoutbox_refresh_interval"><?php echo JText::_('SETTINGS_SHOUTBOX_REFRESH_INTERVAL'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="shoutbox_refresh_interval" id="shoutbox_refresh_interval"
                   value="<?php echo $this->settings->shoutbox_refresh_interval; ?>"/>
        </td>
    </tr>

    <!-- shoutbox_messages -->
    <tr class="shoutbox_related">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="shoutbox_messages"><?php echo JText::_('SETTINGS_SHOUTBOX_NUM_MESSAGES'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="shoutbox_messages" id="shoutbox_messages"
                   value="<?php echo $this->settings->shoutbox_messages; ?>"/>
        </td>
    </tr>

    <!-- shoutbox_log -->
    <?php echo JHtml::_('settings.boolean', 'shoutbox_log', 'SETTINGS_LOG_SHOUTBOX', $this->settings->shoutbox_log); ?>

    <!-- Shoutbox log -->
    <tr>
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label><?php echo JText::_('SETTINGS_SHOUTBOX_LOG'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <a href="<?php echo FactoryRoute::task('downloadshoutboxlog'); ?>"><?php echo JText::sprintf('SETTINGS_SHOUTBOX_LOG_DOWNLOAD', $this->shoutbox_log); ?></a>
            <br/>
            <a href="index.php?option=com_lovefactory&controller=settings&task=emptyshoutboxlog"><?php echo JText::_('SETTINGS_SHOUTBOX_EMPTY_LOG'); ?></a>
        </td>
    </tr>

</table>
