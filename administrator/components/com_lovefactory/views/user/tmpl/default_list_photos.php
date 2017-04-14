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
    <?php foreach ($this->photos as $this->photo): ?>
        <div style="float: left; margin: 10px; ">
            <div class="lovefactory-thumbnail"
                 style="background-image: url('<?php echo $this->photo->getSource(true); ?>');">
                <a href="<?php echo $this->photo->getSource(false); ?>"
                   style="height: 100%; width: 100%; display: block;" target="_blank"></a>
            </div>
            <input type="checkbox" value="<?php echo $this->photo->id; ?>" id="photos_<?php echo $this->photo->id; ?>"
                   name="photos[<?php echo $this->photo->id; ?>]"/> <label for="photos_<?php echo $this->photo->id; ?>"
                                                                           style="display: inline;"><?php echo FactoryText::_('user_photo_label_delete'); ?></label>
        </div>
    <?php endforeach; ?>
</div>
