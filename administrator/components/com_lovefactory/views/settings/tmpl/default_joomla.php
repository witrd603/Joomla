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
        <?php echo JText::_('SETTINGS_ERROR_REPORTING'); ?>
      </span>
        </td>
        <td class="paramlist_value">
            <?php echo $this->errorReporting; ?>
        </td>
    </tr>

    <tr>
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <?php echo JText::_('SETTINGS_SITE_LOCALE_TIME'); ?>
      </span>
        </td>
        <td class="paramlist_value">
            <?php echo JHTML::_('date', 'now', 'l, d F Y, H:i');; ?>
        </td>
    </tr>
</table>
