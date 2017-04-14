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

<div class="factory-view view-configuration" style="margin-top: 20px;">
    <div class="adminform">
        <div class="width-40 fltlft">
            <div class="cpanel">
                <?php foreach ($this->items as $item): ?>
                    <?php if ('' == $item): ?>
                        <div style="clear: both;"></div>
                    <?php else: ?>
                        <div style="width: 100px; height: 100px; float: left; text-align: center;">
                            <a href="<?php echo $item['link']; ?>">
                                <img src="<?php echo $item['image']; ?>"/>
                                <div class="small" style="text-align: center;"><?php echo $item['text']; ?></div>
                            </a>
                        </div>

                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="width-40 fltlft">
            <fieldset>
                <legend><?php echo FactoryText::_('configuration_fieldset_version'); ?></legend>
                <table>
                    <tr>
                        <th><?php echo FactoryText::_('configuration_current_version'); ?></th>
                        <td><?php echo $this->version; ?></td>
                    </tr>

                    <tr class="even">
                        <th><?php echo FactoryText::_('configuration_current_gateways'); ?></th>
                        <td><?php echo $this->gateways ? implode('<br />', $this->gateways) : '-'; ?></td>
                    </tr>
                </table>
            </fieldset>
        </div>
    </div>
</div>
