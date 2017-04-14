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

<div class="factory-view view-dashboard">
    <div class="width-50 fltlft column">
        <?php foreach ($this->order['first'] as $template): ?>
            <?php echo $this->loadTemplate($template); ?>
        <?php endforeach; ?>
    </div>

    <div class="width-50 fltrt column">
        <?php foreach ($this->order['second'] as $template): ?>
            <?php echo $this->loadTemplate($template); ?>
        <?php endforeach; ?>
    </div>
</div>
