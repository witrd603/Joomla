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

<form action="index.php?option=com_lovefactory&view=groups" method="post" name="adminForm" id="adminForm">

    <table class="adminlist table table-striped table-hover">
        <thead>
        <tr>
            <th width="20px"><?php echo JText::_('NUM'); ?></th>
            <th width="20px"><input type="checkbox" name="checkall-toggle" value=""
                                    title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
                                    onclick="Joomla.checkAll(this)"/></th>
            <th class="title"><?php echo JHTML::_('grid.sort', JText::_('GROUPS_GROUP'), 'g.title', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="80px"><?php echo JHTML::_('grid.sort', JText::_('GROUPS_OWNER'), 'u.username', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="80px"><?php echo JHTML::_('grid.sort', JText::_('GROUPS_MEMBERS'), 'members', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <!--			<th width="80px">-->
            <?php //echo JHTML::_('grid.sort', JText::_('GROUPS_POSTS'), 'posts', $this->lists['order_Dir'], $this->lists['order']); ?><!--</th>-->
            <th width="120px"><?php echo JHTML::_('grid.sort', JText::_('GROUPS_CREATED_ON'), 'g.created_at', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="120px"><?php echo JHTML::_('grid.sort', JText::_('GROUPS_PRIVATE'), 'g.private', $this->lists['order_Dir'], $this->lists['order']); ?></th>
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
        <?php foreach ($this->groups as $i => $group): ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td width="20px"><?php echo($i + 1 + $this->pagination->limitstart); ?></td>
                <td width="20px"><?php echo JHTML::_('grid.id', $i, $group->id); ?></td>
                <td>
                    <a href="<?php echo JRoute::_('index.php?option=com_lovefactory&controller=group&task=edit&id=' . $group->id); ?>"><?php echo $group->title; ?></a>
                </td>
                <td>
                    <a href="index.php?option=com_lovefactory&controller=user&task=edit&user_id=<?php echo $group->user_id; ?>"><?php echo $group->owner_username; ?></a>
                </td>

                <td style="text-align: center;">
                    <a href="index.php?option=com_lovefactory&task=groupmembers&id=<?php echo $group->id; ?>"><?php echo $group->members; ?></a>
                </td>
                <!--	      <td style="text-align: center;">-->
                <!--	        <a href="-->
                <?php //echo JURI::root(); ?><!--index.php?option=com_lovefactory&view=group&group_id=-->
                <?php //echo $group->id; ?><!--">--><?php //echo $group->posts; ?><!--</a>-->
                <!--	      </td>-->
                <td style="text-align: center;">
                    <?php echo $group->created_at; ?>
                </td>

                <td style="text-align: center;" class="jgrid">
                    <?php echo JHtml::_('jgrid.published', $group->private, $i, '', false); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <input type="hidden" name="controller" value="group"/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value="groups"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
