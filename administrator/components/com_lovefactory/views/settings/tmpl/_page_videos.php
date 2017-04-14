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
    <!-- videos_embed_allowed_html -->
    <tr class="hasTip"
        title="<?php echo JText::_('SETTINGS_VIDEO_GALLERY_ALLOWED_HTML_EMBED_CODE'); ?>::<?php echo JText::_('SETTINGS_VIDEO_GALLERY_ALLOWED_HTML_EMBED_CODE_TIP'); ?>">
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label
            for="videos_embed_allowed_html"><?php echo JText::_('SETTINGS_VIDEO_GALLERY_ALLOWED_HTML_EMBED_CODE'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input type="text" name="videos_embed_allowed_html" id="videos_embed_allowed_html"
                   value="<?php echo $this->settings->videos_embed_allowed_html; ?>" style="width: 200px;"/>
        </td>
    </tr>
</table>
