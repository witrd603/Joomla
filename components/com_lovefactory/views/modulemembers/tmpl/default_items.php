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

if ($this->items): ?>
    <div class="items">
        <div class="items-wrapper">
            <?php foreach ($this->items as $this->i => $this->item): ?>
                <?php echo $this->loadTemplate('item'); ?>

                <?php if (!(($this->i + 1) % $this->cols) && $this->i || ($this->cols == $this->i + 1)): ?>
                    <div style="clear: left;"></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="module-pagination">
        <?php echo $this->pagination; ?>
    </div>
<?php else: ?>
    <div class="no-results">
        <?php echo JText::_('MOD_LOVEFACTORY_NO_MEMBER_FOUND'); ?>
    </div>
<?php endif; ?>
