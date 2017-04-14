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
    <!-- notification_warning_message -->
    <tr>
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label class="hasTip" for="notification_warning_message"
               title="<?php echo JText::_('Warning message regarding reported message'); ?>::<?php echo JText::_('Warning message regarding reported message'); ?>"><?php echo JText::_('Warning message regarding reported message'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <textarea id="notification_warning_message" name="notification_warning_message" rows="10"
                      cols="60"><?php echo stripslashes($this->settings->notification_warning_message); ?></textarea>
        </td>
    </tr>
</table>
