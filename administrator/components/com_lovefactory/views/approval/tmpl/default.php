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

<style>
    .icon-48-generic {
        background-image: url(<?php echo JURI::root(); ?>components/com_lovefactory/assets/images/love.png);
    }

    .icon-32-save-next {
        background-image: url(<?php echo JURI::root(); ?>components/com_lovefactory/assets/images/icon-32-save-next.png);
    }
</style>

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="width-60 fltlft">
        <fieldset class="adminform">
            <legend><?php echo $this->type; ?></legend>

            <?php if (isset($this->item->user_id) && $this->item->user_id): ?>
                <div style="margin-bottom: 10px;">
                    <a href="<?php echo JRoute::_('index.php?option=com_lovefactory&controller=user&task=edit&user_id=' . $this->item->user_id); ?>">
                        User profile
                    </a>
                </div>
            <?php endif; ?>

            <?php echo $this->loadTemplate($this->type); ?>
        </fieldset>
    </div>

    <div class="width-40 fltlft">
        <fieldset class="adminform">
            <legend>Reject Reason</legend>
            <ul style="list-style-type: none;">
                <li>
                    <textarea rows="10" cols="" name="reject_reason"></textarea>
                </li>
            </ul>

        </fieldset>
    </div>

    <input type="hidden" name="cid[]" value="<?php echo $this->type; ?>.<?php echo $this->id; ?>">
    <input type="hidden" name="controller" value="approval"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="nextitem" value="1"/>
</form>
