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

<style>
    label {
        display: inline;
    }
</style>

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

    <table class="paramlist admintable" style="margin-top: 15px;">
        <!-- default_membership_access -->
        <tr class="hasTip"
            title="<?php echo JText::_('MEMBERSHIPS_RESTRICT_DEFAULT_ACCESS'); ?>::<?php echo JText::_('MEMBERSHIPS_RESTRICT_DEFAULT_ACCESS_TIP'); ?>">
            <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="default_membership_access"><?php echo JText::_('MEMBERSHIPS_RESTRICT_DEFAULT_ACCESS'); ?></label>
      </span>
            </td>
            <td class="paramlist_value">
                <table>
                    <tr>
                        <td>
                            <?php $i = 0;
                            foreach ($this->access as $section => $title): ?>
                            <?php if (!($i % 10)): ?>
                        </td>
                        <td style="padding-right: 30px; ">
                            <?php endif; ?>
                            <input type="checkbox" id="default_membership_access_<?php echo $section; ?>"
                                   name="default_membership_access[<?php echo $section; ?>]" <?php echo (in_array($section, $this->settings->default_membership_access)) ? 'checked' : ''; ?> />
                            <label
                                for="default_membership_access_<?php echo $section; ?>"><?php echo JText::_($title); ?></label><br/>
                            <?php $i++;
                            endforeach; ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <input type="hidden" name="controller" value="settings"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value="save"/>
    <input type="hidden" name="modal" value="1"/>

    <input type="hidden" name="default_membership_access[test123]"/>
</form>

<style>
    td {
        vertical-align: top;
    }
</style>
