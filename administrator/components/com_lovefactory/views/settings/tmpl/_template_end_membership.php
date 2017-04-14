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
    <!-- notification_end_membership_subject -->
    <tr>
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label class="hasTip" for="notification_end_membership_subject"
               title="<?php echo JText::_('Subject'); ?>::<?php echo JText::_('Email subject'); ?>"><?php echo JText::_('Subject'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input style="width: 200px; "
                   value="<?php echo stripslashes($this->settings->notification_end_membership_subject); ?>"
                   id="notification_end_membership_subject" name="notification_end_membership_subject"/>
        </td>
        <td rowspan="2">
            <fieldset>
                <legend><?php echo JText::_('Legend'); ?></legend>
                <table>
                    <tr>
                        <td>%%username%%</td>
                        <td>-</td>
                        <td><?php echo JText::_('Joomla username'); ?></td>
                    </tr>
                    <tr>
                        <td>%%days%%</td>
                        <td>-</td>
                        <td><?php echo JText::_('Days left of current membership'); ?></td>
                    </tr>
                </table>
            </fieldset>
        </td>
    </tr>

    <!-- notification_end_membership_message -->
    <tr>
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label class="hasTip" for="notification_end_membership_message"
               title="<?php echo JText::_('Message'); ?>::<?php echo JText::_('Email message'); ?>"><?php echo JText::_('Message'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <textarea id="notification_end_membership_message" name="notification_end_membership_message" rows="10"
                      cols="60"><?php echo stripslashes($this->settings->notification_end_membership_message); ?></textarea>

            <?php echo JHtml::_('translation.textarea', '', 'notification_end_membership_message', true); ?>
        </td>
    </tr>
</table>
