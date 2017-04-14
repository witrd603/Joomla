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
    <!-- enable_gravatar_integration -->
    <?php echo JHtml::_('settings.boolean', 'enable_gravatar_integration', 'SETTINGS_ENABLE_GRAVATAR', $this->settings->enable_gravatar_integration, 'SETTINGS_ENABLE_GRAVATAR_TIP'); ?>
</table>
