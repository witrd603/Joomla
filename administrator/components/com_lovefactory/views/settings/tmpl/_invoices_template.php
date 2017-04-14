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

echo $this->editor->display(
    'invoice_template',
    $this->settings->invoice_template,
    '100%',
    '550',
    '75',
    '20',
    array('article', 'pagebreak', 'readmore')
); ?>

<div class="clr"></div>

<div style="margin-top: 20px;">
    <?php echo JText::_('COM_LOVEFACTORY_SETTINGS_INVOICES_TEMPLATE_LEGEND'); ?>
</div>
