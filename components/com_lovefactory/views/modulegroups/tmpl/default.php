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

<div class="mod_lovefactory_groups<?php echo $this->moduleClass; ?> lovefactory-module">
    <?php if (!$this->items): ?>
        <?php echo JText::_('MOD_LOVEFACTORY_GROUPS_NO_GROUPS_FOUND'); ?>
    <?php else: ?>
        <table>
            <?php foreach ($this->items as $this->item): ?>
                <tr>
                    <td class="counter"><i
                            class="factory-icon icon-<?php echo 'members' == $this->mode ? 'users' : 'balloon'; ?>"></i><?php echo $this->item->count; ?>
                    </td>
                    <td>
                        <a href="<?php echo FactoryRoute::view('group&id=' . $this->item->id . '&Itemid=' . $this->Itemid); ?>"><?php echo $this->item->title; ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
