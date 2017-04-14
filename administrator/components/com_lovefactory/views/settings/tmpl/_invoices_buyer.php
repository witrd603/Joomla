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

JHTML::_('behavior.modal'); ?>

<?php echo $this->editor->display(
    'invoice_template_buyer',
    $this->settings->invoice_template_buyer,
    '100%',
    '550',
    '75',
    '20',
    array('article', 'pagebreak', 'readmore')
); ?>
