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

if ($this->field->getError() && $this->page->getConfigParam('renderErrorsIndividual')): ?>
    <tr>
        <td colspan="2">
            <div class="lovefactory-field-error">
                <span class="factory-icon icon-exclamation"></span>
                <?php echo $this->field->getError(); ?>
            </div>
        </td>
    </tr>
<?php endif; ?>
