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

<script type="text/javascript">
    Joomla.submitbutton = function (pressbutton) {
        if ('add' == pressbutton || 'edit' == pressbutton) {
            document.getElementById('controller').value = 'report';
        } else {
            document.getElementById('controller').value = 'reports';
        }
        Joomla.submitform(pressbutton);
    }
</script>

<form action="index.php?option=com_lovefactory&view=reports" method="post" name="adminForm" id="adminForm">

    <table width="100%">
        <tr>
            <td align="left" width="50%">
                <label for="search"><?php echo JText::_('REPORTS_FILTER'); ?>:</label>
                <input type="text" id="search" name="search" id="search" value="<?php echo $this->lists['search']; ?>"
                       class="text_area" onchange="document.adminForm.submit();"/>
                <button onclick="this.form.submit();"><?php echo JText::_('REPORTS_GO'); ?></button>
                <button
                    onclick="document.getElementById('search').value='';this.form.getElementById('type').value=0;this.form.getElementById('status').value=-1;this.form.submit();"><?php echo JText::_('REPORST_RESET'); ?></button>
            </td>
            <td style="text-align: right;">
                <?php echo JHTML::_('select.genericlist', $this->get('FilterStatus'), 'status', 'size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->lists['status'], 'filter_status'); ?>
            </td>
        </tr>
    </table>

    <table class="adminlist table table-striped table-hover">
        <thead>
        <tr>
            <th width="20px"><?php echo JText::_('NUM'); ?></th>
            <th width="20px"><input type="checkbox" name="checkall-toggle" value=""
                                    title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
                                    onclick="Joomla.checkAll(this)"/></th>
            <th class="title"><?php echo JText::_('REPORTS_TYPE'); ?></th>
            <th width="12%"><?php echo JHTML::_('grid.sort', JText::_('REPORTS_USERNAME'), 'u.username', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="10%"><?php echo JHTML::_('grid.sort', JText::_('REPORTS_SUBMITED_AT'), 'r.date', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="5%"><?php echo JHTML::_('grid.sort', JText::_('JSTATUS'), 'r.status', $this->lists['order_Dir'], $this->lists['order']); ?></th>
        </tr>
        </thead>

        <tfoot>
        <tr>
            <td colspan="12">
                <?php echo $this->pagination->getLimitBox(); ?><?php echo $this->pagination->getListFooter(); ?>
            </td>
        </tr>
        </tfoot>

        <tbody>
        <?php foreach ($this->reports as $i => $report): ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td width="20px"><?php echo($i + 1 + $this->pagination->limitstart); ?></td>
                <td width="20px"><?php echo JHTML::_('grid.id', $i, $report->id); ?></td>
                <td>
                    <a href="<?php echo JRoute::_('index.php?option=com_lovefactory&controller=report&task=edit&id=' . $report->id); ?>">
                        <?php echo FactoryText::_('report_type_' . $report->element . ('' == $report->type ? '' : '_' . $report->type)); ?>
                    </a>
                </td>
                <td>
                    <a href="<?php echo JRoute::_('index.php?option=com_lovefactory&controller=user&task=edit&user_id=' . $report->user_id); ?>">
                        <?php echo $report->username; ?>
                    </a>
                </td>
                <td class="center"><?php echo JHtml::date($report->date, 'Y-m-d H:i:s'); ?></td>
                <td style="text-align: center;" class="jgrid">
                    <?php echo JHtml::_('jgrid.published', $report->status, $i, '', false); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <input type="hidden" name="controller" value="" id="controller"/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
    <?php echo JHtml::_('form.token'); ?>
</form>
