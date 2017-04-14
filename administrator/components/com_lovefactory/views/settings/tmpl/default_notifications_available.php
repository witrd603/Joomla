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

foreach ($this->notifications as $notification): ?>
    <ul>
        <li>
            <?php echo $notification['name']; ?>:
            <?php foreach ($notification['notifications'] as $item): ?>
                <span style="color: green; font-weight: bold;"><?php echo $item->lang_code; ?></span>,
            <?php endforeach; ?>
        </li>
    </ul>
<?php endforeach; ?>

<a href="index.php?option=com_lovefactory&task=notifications"
   style="font-weight: bold; margin-top: 10px; display: block; font-size: 14px;"><?php echo JText::_('Manage notifications'); ?></a>
