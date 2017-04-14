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

<form action="<?php echo JRoute::_('index.php?option=com_lovefactory&view=payments'); ?>" method="post" name="adminForm"
      id="adminForm">

    <?php echo $this->loadTemplate('filter_bar'); ?>

    <table class="adminlist table table-striped table-hover">
        <thead>
        <?php echo $this->loadTemplate('head'); ?>
        </thead>

        <tfoot>
        <?php echo $this->loadTemplate('foot'); ?>
        </tfoot>

        <tbody>
        <?php foreach ($this->items as $this->i => $this->item): ?>
            <?php echo $this->loadTemplate('body'); ?>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php echo $this->loadTemplate('hidden'); ?>

</form>
