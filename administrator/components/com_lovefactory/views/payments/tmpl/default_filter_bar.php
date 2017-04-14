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

<fieldset id="filter-bar">

    <div class="filter-search fltlft">
        <?php echo $this->loadTemplate('filter_search'); ?>
    </div>

    <div class="filter-select fltrt">
        <?php echo $this->loadTemplate('filter_gateway'); ?>
        <?php echo $this->loadTemplate('filter_status'); ?>
    </div>

</fieldset>

<div class="clr"></div>
