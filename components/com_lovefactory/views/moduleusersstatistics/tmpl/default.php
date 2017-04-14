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

<div class="mod_lovefactory_users_statistics<?php echo $this->moduleClass; ?> lovefactory-module"
     id="lovefactory-module-<?php echo $this->moduleId; ?>">
    <?php if ($this->items->total): ?>
        <?php echo JText::sprintf('MOD_LOVEFACTORY_USERS_STATISTICS_TOTAL_USERS', $this->items->total); ?>

        <table cellpadding="0" cellspacing="0">
            <?php foreach ($this->items->genders as $this->item): ?>
                <tr>
                    <td class="first">
                        <img src="<?php echo $this->item->icon; ?>"/>
                        <?php echo $this->item->genderName; ?>
                    </td>

                    <td class="second">
                        <?php echo $this->item->count; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <?php echo JText::_('MOD_LOVEFACTORY_USERS_STATISTICS_NO_USERS'); ?>
    <?php endif; ?>
</div>
