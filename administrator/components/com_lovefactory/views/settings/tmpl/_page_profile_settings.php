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

<table class="paramlist admintable">
    <?php foreach ($this->forms['profile.settings']->getFieldsets() as $fieldset): ?>
        <?php foreach ($this->forms['profile.settings']->getFieldset($fieldset->name) as $field): ?>
            <tr>
                <td width="40%"><?php echo $field->label; ?></td>
                <td><?php echo $field->input; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
</table>
