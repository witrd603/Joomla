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

<div class="lovefactory-page">
    <?php foreach ($this->page->getZones() as $this->zone): ?>
        <fieldset>
            <legend><?php echo $this->zone['title']; ?></legend>

            <?php echo $this->loadTemplate('zone'); ?>
        </fieldset>
    <?php endforeach; ?>
</div>
