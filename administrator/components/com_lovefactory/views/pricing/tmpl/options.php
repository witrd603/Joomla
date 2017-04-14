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

<form action="index.php" method="post" name="adminForm" id="adminForm">

    <fieldset>
        <div class="fltrt">
            <button type="button" onclick="Joomla.submitform('save', this.form);">
                <?php echo JText::_('JSAVE'); ?></button>
            <button type="button" onclick="Joomla.submitform('apply', this.form);">
                <?php echo JText::_('JAPPLY'); ?></button>
            <button type="button" onclick="window.parent.SqueezeBox.close();">
                <?php echo JText::_('JCANCEL'); ?></button>
        </div>
    </fieldset>

    <table class="paramlist admintable" style="margin-top: 30px;">
        <!-- currency -->
        <tr>
            <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label class="hasTip" for="currency"
               title="<?php echo JText::_('Current currency'); ?>::<?php echo JText::_('Only letters are allowed (eg: USD, EUR)'); ?>"><?php echo JText::_('Current currency'); ?></label>
      </span>
            </td>
            <td class="paramlist_value">
                <input class="hasTip"
                       title="<?php echo JText::_('Current currency'); ?>::<?php echo JText::_('Only letters are allowed (eg: USD, EUR)'); ?>"
                       type="text" name="currency" id="currency" value="<?php echo $this->settings->currency; ?>"/>
            </td>
        </tr>

        <!-- gender_pricing -->
        <tr class="hasTip" for="display_hidden"
            title="<?php echo JText::_('Set prices for each gender'); ?>::<?php echo JText::_('If enabled, the prices for memberships can be set on a gender level'); ?>">
            <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="gender_pricing"><?php echo JText::_('Set prices for each gender'); ?></label>
      </span>
            </td>
            <td class="paramlist_value">
                <select id="gender_pricing" name="gender_pricing">
                    <option
                        value="0" <?php echo (!$this->settings->gender_pricing) ? 'selected="selected"' : ''; ?>><?php echo JText::_('One price for all genders'); ?></option>
                    <option
                        value="1" <?php echo ($this->settings->gender_pricing) ? 'selected="selected"' : ''; ?>><?php echo JText::_('Different prices depending on the gender'); ?></option>
                </select>
            </td>
        </tr>

    </table>

    <input type="hidden" name="controller" value="settings"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value="save"/>
    <input type="hidden" name="modal" value="1"/>
</form>

<style>
    td {
        vertical-align: top;
    }
</style>
