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

<p><?php echo JText::sprintf('SETTINGS_INTEGRATION_RECAPTCHA_TITLE', '<a href="http://recaptcha.net/">ReCaptcha</a>'); ?></p>

<p><?php echo JText::_('SETTINGS_INTEGRATION_RECAPTCHA_TEXT'); ?></p>

<table class="paramlist admintable">
    <!-- enable_recaptcha -->
    <?php echo JHtml::_('settings.boolean', 'enable_recaptcha', 'SETTINGS_ENABLE_RECAPTCHA', $this->settings->enable_recaptcha); ?>

    <!-- recaptcha_public_key -->
    <tr class="recaptcha_related">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="recaptcha_public_key"><?php echo JText::_('SETTINGS_INTEGRATIONS_RECAPTCHA_PUBLIC_KEY'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="recaptcha_public_key" id="recaptcha_public_key"
                   value="<?php echo $this->settings->recaptcha_public_key; ?>" style="width: 250px;"/>
        </td>
    </tr>

    <!-- recaptcha_private_key -->
    <tr class="recaptcha_related">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label
            for="recaptcha_private_key"><?php echo JText::_('SETTINGS_INTEGRATIONS_RECAPTCHA_PRIVATE_KEY'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="recaptcha_private_key" id="recaptcha_private_key"
                   value="<?php echo $this->settings->recaptcha_private_key; ?>" style="width: 250px;"/>
        </td>
    </tr>
</table>
