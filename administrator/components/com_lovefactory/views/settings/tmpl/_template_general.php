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
    <!-- html_notifications -->
    <?php echo JHtml::_('settings.boolean', 'html_notifications', 'SETTINGS_SEND_HTML_NOTIFICATIONS', $this->settings->html_notifications); ?>

    <!-- enable_token_auth -->
    <?php echo JHtml::_('settings.boolean', 'enable_token_auth', 'SETTINGS_USE_TOKEN_AUTH_LINKS', $this->settings->enable_token_auth, 'SETTINGS_USE_TOKEN_AUTH_LINKS_TIP'); ?>
</table>
