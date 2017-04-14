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

<div class="lovefactory-zone">
    <?php echo $this->loadTemplate('pre_zone'); ?>

    <?php foreach ($this->zone['columns'] as $this->column): ?>
        <?php echo $this->loadTemplate('column'); ?>
    <?php endforeach; ?>

    <?php echo $this->loadTemplate('post_zone'); ?>
</div>
