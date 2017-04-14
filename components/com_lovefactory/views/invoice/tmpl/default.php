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

if (!$this->template): ?>
    <?php echo JText::_('COM_LOVEFACTORY_INVOICE_ERROR_NOT_FOUND'); ?>
<?php else: ?>
    <?php echo $this->loadTemplate('print'); ?>
    <?php echo $this->template; ?>
    <?php echo $this->loadTemplate('print'); ?>
<?php endif; ?>
