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

if ($this->field->isRenderable()): ?>
    <?php echo $this->loadTemplate('field_errors'); ?>

    <tr>
        <?php echo $this->loadTemplate('field_label'); ?>
        <?php echo $this->loadTemplate('field_input'); ?>
    </tr>
<?php endif; ?>
