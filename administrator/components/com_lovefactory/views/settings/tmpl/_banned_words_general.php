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
    <!-- enable_banned_words_filter -->
    <?php echo JHtml::_('settings.boolean', 'enable_banned_words_filter', 'SETTINGS_BANNED_WORDS_ENABLE', $this->settings->enable_banned_words_filter); ?>

    <!-- banned_words -->
    <tr class="hasTip"
        title="<?php echo JText::_('SETTINGS_BANNED_WORDS'); ?>::<?php echo JText::_('SETTINGS_BANNED_WORDS_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="banned_words"><?php echo JText::_('SETTINGS_BANNED_WORDS'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <textarea id="banned_words" rows="20" cols="40"
                      name="banned_words"><?php echo $this->banned_words; ?></textarea>
        </td>
    </tr>
</table>
