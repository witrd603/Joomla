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
        <legend><?php echo JText::_('PRICE_PROPERTIES'); ?></legend>

        <table class="table">
            <?php if ($this->price->id): ?>
                <tr>
                    <td><?php echo JText::_('PRICE_ID'); ?>:</td>
                    <td><strong><?php echo $this->price->id; ?></strong></td>
                </tr>
            <?php endif; ?>

            <tr>
                <td class="key" style="width: 20%;"><label
                        for="membership_id"><?php echo JText::_('PRICE_MEMBERSHIP'); ?>:</label></td>
                <td style="width: 80%;"><?php echo $this->membership_select; ?></td>
            </tr>

            <tr>
                <td class="key" align="right"><label for="published"><?php echo JText::_('PRICE_PUBLISHED'); ?>:</label>
                </td>
                <td>
                    <select name="published" id="published">
                        <option
                            value="0" <?php echo (!$this->price->published) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JNO'); ?></option>
                        <option
                            value="1" <?php echo ($this->price->published) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JYES'); ?></option>
                    </select>
                </td>
            </tr>

            <tr>
                <td class="key" align="right"><label for="is_trial"><?php echo JText::_('PRICE_TRIAL'); ?>:</label></td>
                <td>
                    <select name="is_trial" id="is_trial">
                        <option
                            value="0" <?php echo (!$this->price->is_trial) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JNO'); ?></option>
                        <option
                            value="1" <?php echo ($this->price->is_trial) ? 'selected="selected"' : ''; ?>><?php echo JText::_('JYES'); ?></option>
                    </select>
                </td>
            </tr>

            <tr id="row-months">
                <td class="key" align="right"><label for="months"><?php echo JText::_('PRICE_MONTHS'); ?>:</label></td>
                <td>
                    <input type="text" name="months" id="months" value="<?php echo $this->price->months; ?>"/>
                    <span class="editlinktip">
							    <img
                                    src="<?php echo JURI::root(); ?>components/com_lovefactory/assets/images/information.png"
                                    class="hasTip lovefactory_icon2"
                                    title="<?php echo JText::_('PRICE_MONTHS'); ?>::<?php echo JText::_('PRICE_MONTHS_TIP'); ?>"/>
							  </span>
                </td>
            </tr>

            <tr id="row-hours">
                <td class="key" align="right"><label for="months"><?php echo JText::_('PRICE_HOURS'); ?>:</label></td>
                <td>
                    <input type="text" name="months" id="months" value="<?php echo $this->price->months; ?>"/>
                    <span class="editlinktip">
							    <img
                                    src="<?php echo JURI::root(); ?>components/com_lovefactory/assets/images/information.png"
                                    class="hasTip lovefactory_icon2"
                                    title="<?php echo JText::_('PRICE_HOURS'); ?>::<?php echo JText::_('PRICE_HOURS_TIP'); ?>"/>
							  </span>
                </td>
            </tr>

            <tr class="row-trial">
                <td class="key" align="right"><label
                        for="available_interval"><?php echo JText::_('PRICE_AVAILABILITY'); ?>:</label></td>
                <td>
                    <select name="available_interval" id="available_interval">
                        <option
                            value="1" <?php echo $this->price->available_interval ? 'selected="selected"' : ''; ?>><?php echo JText::_('JNO'); ?></option>
                        <option
                            value="0" <?php echo !$this->price->available_interval ? 'selected="selected"' : ''; ?>><?php echo JText::_('JYES'); ?></option>
                    </select>
                </td>
            </tr>

            <tr class="row-trial row-available-interval">
                <td class="key" align="right"><label
                        for="available_from"><?php echo JText::_('PRICE_AVAILABLE_FROM'); ?>:</label></td>
                <td><?php echo JHTML::_('calendar', $this->price->available_from, 'available_from', 'available_from', $format = '%Y-%m-%d %H:%M:%S', array('class' => 'inputbox', 'size' => '25', 'maxlength' => '19')); ?></td>
            </tr>

            <tr class="row-trial row-available-interval">
                <td class="key" align="right"><label
                        for="available_until"><?php echo JText::_('PRICE_AVAILABLE_UNTIL'); ?>:</label></td>
                <td><?php echo JHTML::_('calendar', $this->price->available_until, 'available_until', 'available_until', $format = '%Y-%m-%d %H:%M:%S', array('class' => 'inputbox', 'size' => '25', 'maxlength' => '19')); ?></td>
            </tr>

            <tr class="row-trial">
                <td class="key" align="right"><label for="new_trial"><?php echo JText::_('PRICE_NEW_TRIAL'); ?>:</label>
                </td>
                <td>
                    <select name="new_trial" id="new_trial">
                        <option
                            value="0" <?php echo !$this->price->new_trial ? 'selected="selected"' : ''; ?>><?php echo JText::_('JNO'); ?></option>
                        <option
                            value="1" <?php echo $this->price->new_trial ? 'selected="selected"' : ''; ?>><?php echo JText::_('JYES'); ?></option>
                    </select>
                </td>
            </tr>

            <?php if (!$this->settings->gender_pricing): ?>
                <tr class="row-price">
                    <td class="key" align="right"><label for="price"><?php echo JText::_('PRICE_PRICE'); ?>:</label>
                    </td>
                    <td><input type="text" name="price" id="price"
                               value="<?php echo $this->price->price; ?>"/>&nbsp;<?php echo $this->settings->currency; ?>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($this->genders as $i => $gender): ?>
                    <tr class="hasTip row-price"
                        title="<?php echo JText::sprintf('PRICE_FOR_GENDER', $gender); ?>::<?php echo JText::_('PRICE_FOR_GENDER_TIP'); ?>">
                        <td class="key" align="right"><label
                                for="price_<?php echo $i; ?>"><?php echo JText::sprintf('PRICE_FOR_GENDER', $gender); ?>
                                :</label></td>
                        <td>
							      <span>
							        <input type="text" name="price_<?php echo $i; ?>" id="price_<?php echo $i; ?>"
                                           value="<?php echo isset($this->price->_gender_prices[$i]) ? $this->price->_gender_prices[$i] : '0.00'; ?>"
                                           rel="<?php echo isset($this->price->_gender_prices[$i]) ? $this->price->_gender_prices[$i] : '0.00'; ?>"/>&nbsp;<?php echo $this->settings->currency; ?>
							      </span>
                            <div class="clr"></div>
                            <label
                                for="price_unavailable_<?php echo $i; ?>"><?php echo JText::_('PRICE_UNAVAILABLE'); ?></label><input
                                type="checkbox" id="price_unavailable_<?php echo $i; ?>"
                                class="price_unavailable" <?php echo @$this->price->_gender_prices[$i] == -1 ? 'checked="checked"' : ''; ?>>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

        </table>
    </fieldset>

    <input type="hidden" name="controller" value="price"/>
    <input type="hidden" name="id" value="<?php echo $this->price->id; ?>"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value=""/>
</form>
