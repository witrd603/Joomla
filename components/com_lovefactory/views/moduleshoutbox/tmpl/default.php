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

<div class="mod_lovefactory_shoutbox<?php echo $this->moduleClass; ?> lovefactory-module"
     rel="<?php echo $this->lastUpdate; ?>">
    <?php if (!$this->enabled): ?>
        <?php echo JText::_('MOD_LOVEFACTORY_SHOUTBOX_NOT_ENABLED'); ?>
    <?php elseif (!$this->restriction->isAllowed($this->user->id)): ?>
        <?php echo JText::_('MOD_LOVEFACTORY_SHOUTBOX_NOT_ALLOWED'); ?>
    <?php else: ?>
        <div class="lovefactory-shoutbox-messages" rel="<?php echo $this->settings->shoutbox_refresh_interval; ?>">
            <?php foreach ($this->items as $this->i => $this->item): ?>
                <div class="lovefactory-shoutbox-message <?php echo $this->i % 2 ? 'alternate' : ''; ?>">
                    <?php echo $this->item->html; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($this->restriction->hasFullAccess($this->user->id)): ?>
            <div class="lovefactory-shoutbox-post">
                <form method="post" action="<?php echo FactoryRoute::task('module.shoutboxpostmessage'); ?>">
                    <input type="text" name="message" class="message-user"/>
                    <input type="submit" class="message-submit btn"
                           value="<?php echo JText::_('MOD_LOVEFACTORY_SHOUTBOX_SUBMIT_MESSAGE'); ?>"/>
                </form>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
