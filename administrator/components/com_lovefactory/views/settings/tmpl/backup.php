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

JHTML::_('stylesheet', 'main.css', 'components/com_lovefactory/assets/css/views/backend/'); ?>
<?php JHTML::_('behavior.tooltip'); ?>

<?php JToolBarHelper::title(JText::_('SETTINGS_PAGE_TITLE_BACKUP'), 'generic.png'); ?>

<?php JToolBarHelper::cancel(); ?>

<style>
    .icon-48-generic {
        background-image: url(<?php echo JURI::root(); ?>components/com_lovefactory/assets/images/love.png);
    }

    td {
        vertical-align: top;
    }
</style>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <div class="width-50 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('SETTINGS_TAB_BACKUP'); ?></legend>
            <?php require_once('_backup.php'); ?>
        </fieldset>
    </div>

    <div class="width-50 fltrt">
        <fieldset class="adminform">
            <legend><?php echo JText::_('SETTINGS_TAB_RESTORE'); ?></legend>
            <?php require_once('_restore.php'); ?>
        </fieldset>
    </div>

    <input type="hidden" name="controller" value="settings"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value=""/>
</form>
