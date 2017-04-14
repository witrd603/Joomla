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
    <!-- enable_interaction_kiss -->
    <?php echo JHtml::_('settings.boolean', 'enable_interaction_kiss', 'SETTINGS_ENABLE_INTERACTION_KISS', $this->settings->enable_interaction_kiss); ?>

    <!-- enable_interaction_wink -->
    <?php echo JHtml::_('settings.boolean', 'enable_interaction_wink', 'SETTINGS_ENABLE_INTERACTION_WINK', $this->settings->enable_interaction_wink); ?>

    <!-- enable_interaction_hug -->
    <?php echo JHtml::_('settings.boolean', 'enable_interaction_hug', 'SETTINGS_ENABLE_INTERACTION_HUG', $this->settings->enable_interaction_hug); ?>
</table>
