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

<?php JToolBarHelper::title(JText::_('SETTINGS_PAGE_TITLE_ADVERTISING'), 'generic.png'); ?>

<?php JToolBarHelper::cancel('cancel', 'Close'); ?>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <p><?php echo JText::_('SETTINGS_TAB_ADVERTISING_INFO'); ?></p>
    <div class="width-100 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('SETTINGS_TAB_AVAILABLE'); ?></legend>
            <?php require_once('_ad_sense_available.php'); ?>
        </fieldset>

        <fieldset class="adminform">
            <legend><?php echo JText::_('SETTINGS_TAB_ADD_NEW'); ?></legend>
            <?php require_once('_ad_sense_new.php'); ?>
        </fieldset>
    </div>

    <div class="clr"></div>

    <input type="hidden" name="controller" value="settings"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value=""/>
</form>
