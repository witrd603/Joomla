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

<div class="box" id="portlet_users">
    <div class="header2">
        <?php echo FactoryText::_('dashboard_box_users'); ?>
        <div style="display: none;" class="factory-icon icon-minus-circle minimize"></div>
    </div>

    <div class="content">
        <table>
            <tbody>
            <tr class="even">
                <td>
                    <?php echo FactoryText::_('dashboard_box_users_total_users'); ?>
                </td>

                <td class="center">
                    <span class="label label-success"><?php echo $this->users['total']; ?></span>
                </td>
            </tr>

            <tr class="">
                <td>
                    <?php echo FactoryText::_('dashboard_box_users_banned_users'); ?>
                </td>

                <td class="center">
                    <span class="label label-important"><?php echo (int)$this->users['banned']; ?></span>
                </td>
            </tr>

            <tr class="">
                <td colspan="2" style="height: 30px;">
                </td>
            </tr>

            <?php foreach ($this->memberships as $i => $membership): ?>
                <tr class="<?php echo $i % 2 ? 'even' : ''; ?>">
                    <td>
                        <?php echo $membership->title; ?>
                    </td>

                    <td class="center">
                        <span><?php echo $membership->count; ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
