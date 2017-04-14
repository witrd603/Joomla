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

<script type="text/javascript">
    Joomla.submitbutton = function (pressbutton) {

        if (false !== pressbutton.indexOf('.')) {
            var split = pressbutton.split('.');
            pressbutton = split[1];
            document.getElementById('controller').value = split[0];
        }

        Joomla.submitform(pressbutton);
    }

    function submitbutton(pressbutton) {
        Joomla.submitbutton(pressbutton);
    }
</script>

<style>
    label {
        display: inline;
    }
</style>

<form action="<?php echo JRoute::_('index.php?option=com_lovefactory&view=notifications'); ?>" method="post"
      name="adminForm" id="adminForm">

    <?php echo $this->loadTemplate('filters'); ?>

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th width="1%">
                <input type="checkbox" name="checkall-toggle" value=""
                       title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
            </th>

            <th>
                <?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'n.subject', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
            </th>

            <th width="20%">
                <?php echo JHtml::_('grid.sort', 'COM_LOVEFACTORY_NOTIFICATIONS_TYPE', 'n.type', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
            </th>

            <th width="10%">
                <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'n.lang_code', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
            </th>

            <th width="5%">
                <?php echo JHtml::_('grid.sort', 'JSTATUS', 'n.published', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
            </th>

            <th width="1%" class="nowrap">
                <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'n.id', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
            </th>
        </tr>
        </thead>

        <tfoot>
        <tr>
            <td colspan="15">
                <?php echo $this->pagination->getLimitBox(); ?><?php echo $this->pagination->getListFooter(); ?>
            </td>
        </tr>
        </tfoot>

        <tbody>
        <?php foreach ($this->items as $this->i => $item): ?>
            <tr>
                <td><?php echo JHtml::_('grid.id', $this->i, $item->id); ?></td>

                <td>
                    <a href="<?php echo JRoute::_('index.php?option=com_lovefactory&controller=notification&task=edit&id=' . $item->id); ?>"><?php echo $this->escape($item->subject); ?></a>
                </td>

                <td><?php echo FactoryText::_('notification_' . $item->type); ?></td>

                <td>
                    <?php if ($item->lang_code == '*'): ?>
                        <?php echo JText::alt('JALL', 'language'); ?>
                    <?php else: ?>
                        <?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
                    <?php endif; ?>
                </td>

                <td class="center"><?php echo JHtml::_('jgrid.published', $item->published, $this->i, 'notifications.'); ?></td>

                <td class="center"><?php echo $item->id; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div>
        <input type="hidden" name="controller" id="controller" value=""/>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="boxchecked" value="0"/>
        <input type="hidden" name="filter_order" value="<?php echo $this->state->get('list.ordering'); ?>"/>
        <input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->get('list.direction'); ?>"/>
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
