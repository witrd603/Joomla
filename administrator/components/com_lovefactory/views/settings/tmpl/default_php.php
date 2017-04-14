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
    <tr>
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <?php echo JText::_('SETTINGS_DISPLAY_ERRORS'); ?>
      </span>
        </td>
        <td class="paramlist_value">
            <?php echo ini_get('display_errors') ? JText::_('JYES') : JText::_('JNO');; ?>
        </td>
    </tr>

    <tr>
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <?php echo JText::_('SETTINGS_FILE_UPLOADS'); ?>
      </span>
        </td>
        <td class="paramlist_value">
            <?php echo ini_get('file_uploads') ? JText::_('JYES') : JText::_('JNO'); ?>
        </td>
    </tr>

    <tr>
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <?php echo JText::_('SETTINGS_MAX_UPLOAD_SIZE'); ?>
      </span>
        </td>
        <td class="paramlist_value">
            <?php echo ini_get('upload_max_filesize'); ?>
        </td>
    </tr>

    <tr>
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <?php echo JText::_('SETTINGS_GMT_TIME'); ?>
      </span>
        </td>
        <td class="paramlist_value">
            <?php echo gmdate('l, d F Y H:i', time()); ?>
        </td>
    </tr>
</table>
