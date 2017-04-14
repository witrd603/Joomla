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
    <!-- enable_youtube_integration -->
    <?php echo JHtml::_('settings.boolean', 'enable_youtube_integration', 'SETTINGS_ENABLE_YOUTUBE', $this->settings->enable_youtube_integration); ?>

    <!-- YouTube Api Key -->
    <tr class="youtube_integration_related">

        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="youtube_api_key"><?php echo JText::_('SETTINGS_YOUTUBE_API_KEY'); ?></label>
      </span>
        </td>

        <td class="paramlist_value">
            <a href="https://developers.google.com/youtube/registering_an_application?hl=en"><?php echo JText::_('SETTINGS_GOOGLE_MAPS_GET_KEY'); ?></a>
            <br/>
            <input type="text" name="youtube_api_key" id="youtube_api_key"
                   value="<?php echo $this->settings->youtube_api_key; ?>" style="width: 250px;"/>
        </td>
    </tr>
</table>
