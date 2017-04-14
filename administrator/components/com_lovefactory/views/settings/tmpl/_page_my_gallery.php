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
    <!-- my_gallery_action_links -->
    <?php echo JHtml::_(
        'settings.boolean',
        'my_gallery_action_links',
        'SETTINGS_SHOW_ACTION_LINKS',
        $this->settings->my_gallery_action_links,
        null,
        array(
            0 => 'SETTINGS_SHWO_ACTION_LINKS_ALWAYS',
            1 => 'SETTINGS_SHWO_ACTION_LINKS_HOVER',
        )
    ); ?>
</table>
