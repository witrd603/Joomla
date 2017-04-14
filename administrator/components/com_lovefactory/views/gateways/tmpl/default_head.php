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

<tr>
    <th width="1%">
        <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
               onclick="Joomla.checkAll(this)"/>
    </th>

    <th>
        <?php echo JHtml::_('grid.sort', 'COM_LOVEFACTORY_GATEWAYS_TITLE', 'g.title', $this->listDirn, $this->listOrder); ?>
    </th>

    <th width="8%">
        <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'ordering', $this->listDirn, $this->listOrder); ?>

        <?php if ('ordering' == $this->listOrder): ?>
            <?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'gateways.saveorder'); ?>
        <?php endif; ?>
    </th>

    <th width="10%">
        <?php echo JHtml::_('grid.sort', 'COM_LOVEFACTORY_GATEWAYS_ADDON', 'g.element', $this->listDirn, $this->listOrder); ?>
    </th>

    <th width="8%">
        <?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'g.published', $this->listDirn, $this->listOrder); ?>
    </th>
</tr>
