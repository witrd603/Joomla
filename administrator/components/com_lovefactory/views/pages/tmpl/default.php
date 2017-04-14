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

    .icon-back:before {
        content: "\e008";
    }
</style>

<form action="index.php?option=com_lovefactory&view=pages" method="post" name="adminForm"
      style="width: 98%; margin: 0px auto;" id="adminForm">

    <table>
        <tr>
            <td align="left" width="100%">
                <label for="search" style="display: inline;"><?php echo JText::_('PAGES_FILTER'); ?>:</label>
                <input type="text" id="search" name="search" id="search" value="<?php echo $this->lists['search']; ?>"
                       class="text_area" onchange="document.adminForm.submit();"/>
                <button onclick="this.form.submit();"><?php echo JText::_('PAGES_GO'); ?></button>
                <button
                    onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('PAGES_RESET'); ?></button>
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
            <th width="200px"><?php echo JHTML::_('grid.sort', JText::_('PAGES_TITLE'), 'title', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th class="title"><?php echo JText::_('PAGES_INFO'); ?></th>
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
        <?php foreach ($this->pages as $i => $page): ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td width="20px"><?php echo($i + 1 + $this->pagination->limitstart); ?></td>
                <td width="20px"><?php echo JHTML::_('grid.id', $i, $page->id); ?></td>
                <td>
                    <a href="<?php echo JRoute::_('index.php?option=com_lovefactory&controller=page&task=edit&id=' . $page->id); ?>">
                        <?php echo $page->title; ?>
                    </a>
                </td>
                <td><?php echo FactoryText::_('page_description_' . $page->type); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <input type="hidden" name="controller" value="page"/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value="edit"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
