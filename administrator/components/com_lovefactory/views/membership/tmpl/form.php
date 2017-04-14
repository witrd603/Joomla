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

JHtml::_('formbehavior.chosen', 'select');

?>

<style>
    label {
        display: inline;
    }

    div.field-countable-restriction input[type="text"] {
        width: 100px;
        margin-left: 20px;
    }

    div.field-countable-restriction label,
    div.field-countable-restriction input {
        margin: 0;
    }

    div.field-countable-restriction label {
        vertical-align: baseline;
    }

    div.field-countable-restriction label input[type="checkbox"] {
        margin-right: 5px;
        margin-top: -5px;
    }
</style>

<form action="index.php" method="post" name="adminForm" id="adminForm">

    <h2>Membership</h2>

    <div class="row-fluid">
        <div class="span5">
            <h3>Details</h3>

            <?php foreach ($this->form->getFieldset('details') as $field): ?>
                <?php echo $field->renderField(); ?>
            <?php endforeach; ?>
        </div>

        <div class="span7 form-horizontal">
            <h3>Restrictions</h3>

            <?php foreach ($this->form->getFieldset('restrictions') as $field): ?>
                <?php echo $field->renderField(); ?>
            <?php endforeach; ?>
        </div>
    </div>

    <input type="hidden" name="controller" value="membership"/>
    <input type="hidden" name="id" value="<?php echo $this->membership->id; ?>"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value="save"/>
</form>
