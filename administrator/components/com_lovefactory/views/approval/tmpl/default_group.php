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

<ul>
    <li>
        <label>Title</label>
        <?php echo $this->item->title; ?>
    </li>

    <li>
        <label>Description</label>
        <?php echo $this->item->description; ?>
    </li>

    <!--  <li>-->
    <!--    <label>Photo</label>-->
    <!--    <img src="--><?php //echo $this->item->getThumbnail(); ?><!--" />-->
    <!--  </li>-->
</ul>
