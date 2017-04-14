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

if (!$this->chatfactory): ?>
    <?php echo JText::_('SETTINGS_CHATFACTORY_INTEGRATION_INSTALL_FIRST'); ?> <a href="http://www.thephpfactory.com">thePHPfactory.com</a>
<?php else: ?>

    <table class="paramlist admintable">
        <!-- enable_chatfactory_integration -->
        <?php echo JHtml::_('settings.boolean', 'enable_chatfactory_integration', 'SETTINGS_ENABLE_CHATFACTORY', $this->settings->enable_chatfactory_integration); ?>

        <!-- chatfactory_integration_users_list -->
        <?php echo JHtml::_(
            'settings.boolean',
            'chatfactory_integration_users_list',
            'SETTINGS_CHATFACTORY_USERS_LIST',
            $this->settings->chatfactory_integration_users_list,
            null,
            array(
                0 => 'SETTINGS_CHATFACTORY_SHOW_FRIENDS',
                1 => 'SETTINGS_CHATFACTORY_SHOW_ALL'
            ),
            null,
            'chatfactory_integration'
        ); ?>

        <!-- chatfactory_integration_delete_user -->
        <!--    --><?php //echo JHtml::_(
        //      'settings.boolean',
        //      'chatfactory_integration_delete_user',
        //      'SETTINGS_CHATFACTORY_DELETE_USER',
        //      $this->settings->chatfactory_integration_delete_user,
        //      'SETTINGS_CHATFACTORY_DELETE_USER_TIP',
        //      null,
        //      null,
        //      'chatfactory_integration'
        //    ); ?>
        <!---->
        <!--    <tr class="chatfactory_integration_related">-->
        <!--      <td><span class="lovefactory-button lovefactory-bullet-error lovefactory-error-field">-->
        <?php //echo JText::_('SETTINGS_CHATFACTORY_WARNING'); ?><!--</span></td>-->
        <!--    </tr>-->
    </table>
<?php endif; ?>
