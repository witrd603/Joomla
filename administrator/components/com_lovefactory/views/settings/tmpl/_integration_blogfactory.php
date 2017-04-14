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

if (!$this->blogfactory): ?>
    <?php echo JText::_('SETTINGS_BLOGFACTORY_INTEGRATION_INSTALL_FIRST'); ?> <a href="http://www.thephpfactory.com">thePHPfactory.com</a>
<?php else: ?>

    <table class="paramlist admintable">
        <!-- enable_blogfactory_integration -->
        <?php echo JHtml::_('settings.boolean', 'enable_blogfactory_integration', 'SETTINGS_ENABLE_BLOGFACTORY', $this->settings->enable_blogfactory_integration); ?>
    </table>
<?php endif; ?>
