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

<div class="slider-table-wrapper">
    <?php foreach ($this->videos as $this->video): ?>
        <div style="margin-bottom: 10px;">
            <div><?php echo $this->video->code; ?></div>
            <input type="checkbox" value="<?php echo $this->video->id; ?>" id="videos_<?php echo $this->video->id; ?>"
                   name="videos[<?php echo $this->video->id; ?>]"/> <label style="display: inline;"
                                                                           for="videos_<?php echo $this->video->id; ?>"><?php echo FactoryText::_('user_video_label_delete'); ?></label>
        </div>
    <?php endforeach; ?>
</div>
