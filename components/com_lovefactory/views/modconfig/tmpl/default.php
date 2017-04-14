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

<div class="lovefactory-module-configuration-wrapper">
    <form>
        <ul>
            <?php foreach ($this->availableGenders as $id => $gender): ?>
                <li>
                    <input
                        type="checkbox"
                        id="gender_<?php echo $this->moduleId ?>_<?php echo $id; ?>"
                        name="gender" value="<?php echo $id; ?>"
                        <?php echo in_array($id, $this->userConfiguration->gender) ? 'checked="checked"' : ''; ?>
                    />
                    <label for="gender_<?php echo $this->moduleId ?>_<?php echo $id; ?>"><?php echo $gender; ?></label>
                </li>
            <?php endforeach; ?>
        </ul>
    </form>

    <small><?php echo JText::_('COM_LOVEFACTORY_MODULES_CONFIGURATION_GENDER_WARNING'); ?></small>

    <div class="lovefactory-module-configuration-buttons">
        <input type="submit" class="btn" value="<?php echo JText::_('MOD_LOVEFACTORY_CONFIGURE_SUBMIT'); ?>"/>
        <a href="#" class=""><?php echo JText::_('MOD_LOVEFACTORY_CONFIGURE_CANCEL'); ?></a>
    </div>

    <div class="lovefactory-module-configuration-loading" style="display: none;"></div>
</div>
