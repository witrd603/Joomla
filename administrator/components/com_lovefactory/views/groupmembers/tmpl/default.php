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
    .icon-48-generic {
        background-image: url(<?php echo JURI::root(); ?>components/com_lovefactory/assets/images/love.png);
    }
</style>

<form action="index.php" method="post" name="adminForm" id="adminForm">

    <table class="adminlist table table-striped table-hover">
        <thead>
        <tr>
            <th width="20px"><?php echo JText::_('NUM'); ?></th>
            <th width="20px"><input type="checkbox" name="toggle" value=""
                                    onclick="checkAll(<?php echo count($this->members); ?>);"/></th>
            <th class="title"><?php echo JHTML::_('grid.sort', JText::_('GROUPMEMBERS_MEMBER'), 'u.username', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <!--			<th width="60px">-->
            <?php //echo JHTML::_('grid.sort', JText::_('GROUPMEMBERS_POSTS'), 'posts', $this->lists['order_Dir'], $this->lists['order']); ?><!--</th>-->
            <th width="120px"><?php echo JHTML::_('grid.sort', JText::_('GROUPMEMBERS_JOINED_ON'), 'm.created_at', $this->lists['order_Dir'], $this->lists['order']); ?></th>
        </tr>
        </thead>

        <tfoot>
        <tr>
            <td colspan="20">
                <?php echo $this->pagination->getLimitBox(); ?><?php echo $this->pagination->getListFooter(); ?>
            </td>
        </tr>
        </tfoot>

        <tbody>
        <?php foreach ($this->members as $i => $member): ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td width="20px"><?php echo($i + 1 + $this->pagination->limitstart); ?></td>
                <td width="20px"><?php echo JHTML::_('grid.id', $i, $member->id); ?></td>
                <td>
                    <a href="index.php?option=com_lovefactory&controller=user&task=edit&user_id=<?php echo $member->user_id; ?>"><?php echo $member->username; ?></a>
                </td>
                <!--	      <td style="text-align:center;">--><?php //echo $member->posts; ?><!--</td>-->
                <td style="text-align:center;"><?php echo $member->created_at; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <input type="hidden" name="controller" value="groupmember"/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value="groupmembers"/>
    <input type="hidden" name="id" value="<?php echo $this->group_id; ?>"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
