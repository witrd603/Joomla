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
        if ('add' == pressbutton || 'edit' == pressbutton) {
            document.getElementById('controller').value = 'field';
        } else {
            document.getElementById('controller').value = 'fields';
        }
        Joomla.submitform(pressbutton);
    }

    function useField(id, title) {
        <?php if ('invoice' == JFactory::getApplication()->input->getCmd('type')): ?>
        window.parent.tinyMCE.setActive(window.parent.tinymce.get('invoice_template_buyer'));
        window.parent.jInsertEditorText('%%' + id + '__' + title + '%%</span>', 'invoice_template_buyer');
        <?php else: ?>
        var zone = <?php echo JFactory::getApplication()->input->getInt('zone'); ?>;
        var column = <?php echo JFactory::getApplication()->input->getInt('column'); ?>;

        window.parent.jQuery.insertField(id, title, zone, column);
        <?php endif; ?>

        window.parent.SqueezeBox.close();

        return false;
    }
</script>

<form action="index.php?option=com_lovefactory&view=fields" method="post" name="adminForm"
      style="width: 98%; margin: 0px auto;" id="adminForm">

    <fieldset id="filter-bar">
        <div class="fltlft" style="float: left;">
            <label for="search" style="display: inline;"><?php echo JText::_('FIELDS_FILTER'); ?>:</label>
            <input type="text" id="search" name="search" id="search" value="<?php echo $this->lists['search']; ?>"
                   class="text_area" onchange="document.adminForm.submit();"/>
            <button onclick="this.form.submit();"><?php echo JText::_('FIELDS_GO'); ?></button>
            <button
                onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_('FIELDS_RESET'); ?></button>
        </div>

        <!--      <div class="fltrt" style="float: right;">-->
        <!--			  --><?php //echo JHTML::_('grid.state', $this->lists['state']); ?>
        <!--      </div>-->
    </fieldset>

    <table class="adminlist table table-striped table-hover">
        <thead>
        <tr>
            <th width="20px"><?php echo JText::_('NUM'); ?></th>
            <th class="title"><?php echo JHTML::_('grid.sort', JText::_('FIELDS_TITLE'), 'title', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="20%"><?php echo JHTML::_('grid.sort', JText::_('FIELDS_TYPE'), 'type', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="8%"><?php echo JHTML::_('grid.sort', JText::_('FIELDS_REQUIRED'), 'required', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="8%"><?php echo JHTML::_('grid.sort', JText::_('FIELDS_PUBLISHED'), 'published', $this->lists['order_Dir'], $this->lists['order']); ?></th>
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
        <?php foreach ($this->fields as $i => $field): ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td width="20px"><?php echo($i + 1 + $this->pagination->limitstart); ?></td>
                <td>
                    <a href="#"
                       onclick="useField(<?php echo $field->id; ?>, '<?php echo $field->title; ?>'); return false;">
                        <?php echo $field->title; ?>
                    </a>
                </td>
                <td><?php echo $field->type; ?></td>

                <td style="text-align: center;" class="jgrid">
                    <?php echo JHtml::_('jgrid.published', $field->required, $i, '', false); ?>
                </td>

                <td style="text-align: center;" class="jgrid">
                    <?php echo JHtml::_('jgrid.published', $field->published, $i, '', false); ?>
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
    <input type="hidden" name="tmpl" value="component">
    <input type="hidden" name="layout" value="modal">
    <input type="hidden" name="zone" value="<?php echo JFactory::getApplication()->input->getInt('zone'); ?>">
    <input type="hidden" name="column" value="<?php echo JFactory::getApplication()->input->getInt('column'); ?>">
    <input type="hidden" name="type" value="<?php echo JFactory::getApplication()->input->getCmd('type'); ?>"/>
    <?php echo JHtml::_('form.token'); ?>
</form>
