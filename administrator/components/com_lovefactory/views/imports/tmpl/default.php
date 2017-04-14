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

if (!$this->items): ?>
    <?php echo FactoryText::_('imports_no_items_found'); ?>
<?php else: ?>
    <ul>
        <?php foreach ($this->items as $item): ?>
            <li>
                <?php echo $item->getName(); ?>
                &mdash;
                <a href="<?php echo JRoute::_('index.php?option=com_lovefactory&view=import&adaptor=' . $item->getAdaptor()); ?>">
                    <?php if ($item->getCurrentJob()): ?>
                        <?php echo FactoryText::_('imports_import_resume'); ?>
                    <?php else: ?>
                        <?php echo FactoryText::_('imports_import_start'); ?>
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
