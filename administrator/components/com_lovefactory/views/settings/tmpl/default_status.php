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

<ul class="adminformlist">
    <li>
        <label><?php echo JText::_('SETTINGS_FILE_STATUS'); ?>:</label>
        <div style="float: left; margin: 5px 0; margin-top: 8px;">
            <?php if ($this->writable): ?>
                <b style="color: green;"><?php echo JText::_('SETTINGS_FILE_STATUS_WRITABLE'); ?></b>
            <?php else: ?>
                <b style="color: red;"><?php echo JText::_('SETTINGS_FILE_STATUS_NOT_WRITABLE'); ?></b> (<?php echo JPATH_COMPONENT_ADMINISTRATOR . DS . 'settings.php'; ?>)
            <?php endif; ?>
        </div>
    </li>

    <?php foreach ($this->pluginsStatus as $name => $status): ?>
        <li>
            <label><?php echo JText::_('SETTINGS_PLUGIN_STATUS_' . strtoupper($name)); ?>:</label>
            <div style="float: left; margin: 5px 0; margin-top: 8px;">
                <?php echo JText::_('SETTINGS_PLUGIN_STATUS_' . $status); ?>
            </div>
        </li>
    <?php endforeach; ?>

    <li>
        <label><?php echo JText::_('SETTINGS_STORAGE_FOLDER'); ?>:</label>
        <div style="float: left; margin: 5px 0; margin-top: 8px;">
            <?php $path = $this->app->getStorageFolder(); ?>
            <b style="color: <?php echo JFolder::exists($path) ? 'green' : 'red'; ?>;"><?php echo JFolder::exists($path) ? JText::_('SETTINGS_STORAGE_FOLDER_EXISTS') : JText::_('SETTINGS_STORAGE_FOLDER_DOES_NOT_EXIST'); ?></b><br/>
            <div style="color: #999999;"><?php echo $path; ?></div>
        </div>
    </li>

    <li>
        <label><?php echo JText::_('SETTINGS_REGISTRATION_PHOTOS_FOLDER'); ?>:</label>
        <div style="float: left; margin: 5px 0; margin-top: 8px;">
            <?php $path = $this->app->getRegistrationFolder(); ?>
            <b style="color: <?php echo JFolder::exists($path) ? 'green' : 'red'; ?>;"><?php echo JFolder::exists($path) ? JText::_('SETTINGS_STORAGE_FOLDER_EXISTS') : JText::_('SETTINGS_STORAGE_FOLDER_DOES_NOT_EXIST'); ?></b><br/>
            <div style="color: #999999;"><?php echo $path; ?></div>
        </div>
    </li>

    <li>
        <label><?php echo JText::_('SETTINGS_STATUS_GD_LIBRARY'); ?>:</label>
        <div style="float: left; margin: 5px 0; margin-top: 8px;">
            <?php $enabled = extension_loaded('gd'); ?>
            <b style="color: <?php echo $enabled ? 'green' : 'red'; ?>;"><?php echo $enabled ? JText::_('SETTINGS_STATUS_ENABLED') : JText::_('SETTINGS_STATUS_NOT_ENABLED'); ?></b><br/>
        </div>
    </li>
</ul>
