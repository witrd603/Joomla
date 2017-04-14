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

echo JHtml::stylesheet('components/com_lovefactory/assets/css/main.css'); ?>

<div class="lovefactory-view">
    <form action="index.php" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
        <?php echo $this->renderer->render($this->page); ?>

        <input type="hidden" name="controller" value="user"/>
        <input type="hidden" name="option" value="com_lovefactory"/>
        <input type="hidden" name="task" value="create"/>

        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
