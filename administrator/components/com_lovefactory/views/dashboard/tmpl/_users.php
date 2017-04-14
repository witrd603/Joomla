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

<table class="adminlist">
    <thead>
    <tr>
        <th style="width: 70%"><?php echo JText::_('DASHBOARD_USERS_TYPE'); ?></th>
        <th style="text-align: center;"><?php echo JText::_('DASHBOARD_USERS_COUNT'); ?></th>
    </tr>
    </thead>

    <tbody>
    <tr>
        <td><?php echo JText::_('DASHBOARD_USERS_TOTAL_USERS'); ?></td>
        <td style="text-align: center; font-weight: <?php echo $this->users['total'] ? 'bold' : ''; ?>"><?php echo $this->users['total']; ?></td>
    </tr>

    <tr>
        <td><?php echo JText::_('DASHBOARD_USERS_BANNED_USERS'); ?></td>
        <td style="text-align: center; font-weight: <?php echo $this->users['banned'] ? 'bold' : ''; ?>"><?php echo $this->users['banned']; ?></td>
    </tr>

    <tr>
        <td colspan="2"></td>
    </tr>

    <?php foreach ($this->memberships as $membership): ?>
        <tr>
            <td><?php echo $membership->title; ?><?php echo JText::_('DASHBOARD_USERS_MEMBERSHIP'); ?></td>
            <td style="text-align: center; font-weight: <?php echo $membership->users ? 'bold' : ''; ?>"><?php echo $membership->users; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>

    <tfoot>
    <tr>
        <td colspan="10" style="padding-top: 10px;"><a
                href="<?php echo JRoute::_('index.php?option=com_lovefactory&task=users'); ?>"><?php echo JText::_('DASHBOARD_USERS_ALL_USERS'); ?>
        </td>
    </tr>
    </tfoot>
</table>
