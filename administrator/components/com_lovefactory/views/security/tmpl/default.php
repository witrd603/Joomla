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

<style>
    form#adminForm label {
        margin: 0;
    }

    form#adminForm div.control-label {
        padding: 0;
    }

    div.restriction {
        display: inline-block;
        margin-bottom: 5px;
        margin-right: 5px;
    }

    div.restriction span {
        background-color: #d9edf7;
        display: inline-block;
        padding: 4px 4px 4px 8px;
        border-radius: 4px 0 0 4px;
        color: #3a87ad;
        font-size: 12px;
        border-style: solid;
        border-color: #bce8f1;
        border-width: 1px 0 1px 1px;
    }

    div.restriction a {
        background-color: #d9edf7;
        display: inline-block;
        padding: 4px 8px 4px 8px;
        border-radius: 0 4px 4px 0;
        color: #3a87ad;
        font-size: 12px;
        font-weight: bold;
        border-style: solid;
        border-color: #bce8f1;
        border-width: 1px 1px 1px 0;
    }

    div.restriction.fixed span {
        border-radius: 4px;
        padding: 4px 8px;
        background-color: #eeeeee;
        border-color: #dddddd;
        color: #999999;
        border-width: 1px;
    }

    div.restriction a:hover {
        text-decoration: none;
        background-color: #f2dede;
        color: #b94a48;
    }

    form#adminForm select {
        width: auto;
    }
</style>

<script>
    jQuery(document).ready(function ($) {
        $(document).on('click', 'div.restriction a', function (event) {
            event.preventDefault();

            $(this).parents('div.restriction:first').remove();

            update();
        });

        $('[data-action="restriction"]').click(function (event) {
            event.preventDefault();

            $(this).hide().next().show();
        });

        $('[data-action="cancel"]').click(function (event) {
            event.preventDefault();

            $(this).parents('div.actions').hide().prev().show();
        });

        $('[data-action="add"]').click(function (event) {
            event.preventDefault();

            var $select = $(this).prev();
            var value = $select.val();
            var text = $select.find('option:selected').text();
            var section = $select.parents('[data-section]').data('section');

            if ($(this).parents('div.controls').find('div.restrictions [data-restriction="' + value + '"]').length) {
                return false;
            }

            $(this).parents('div.controls').find('div.restrictions')
                .append(
                    '<div class="restriction" data-restriction="' + value + '">' +
                    '<span>' + text + '</span>' +
                    '<a href="#">&times;</a>' +
                    '<input type="hidden" name="restriction[' + section + '][]" value="' + value + '">' +
                    '</div>'
                );

            update();
        });

        function update() {
            $('div.restrictions').each(function (index, element) {
                var $empty = $(element).find('div.empty');

                if ($(element).find('div.restriction').length) {
                    $empty.hide();
                }
                else {
                    $empty.show();
                }
            });
        }

        update();
    });
</script>

<script>
    Joomla.submitbutton = function (pressbutton) {
        var split = pressbutton.split('.');

        document.getElementById('controller').value = split[0];
        pressbutton = split[1];

        Joomla.submitform(pressbutton);
    }
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">

    <h2>Sections</h2>

    <div class="row-fluid">
        <div class="span12 form-horizontal">
            <?php foreach ($this->sections as $name => $restrictions): ?>
                <div class="control-group" data-section="<?php echo $name; ?>">
                    <div class="control-label">
                        <label><?php echo $restrictions['text']['label']; ?></label>
                    </div>

                    <div class="controls">
                        <div class="restrictions">
                            <div class="empty" style="display: none;">No restrictions!</div><!--

              <?php foreach ($restrictions['fixedRules'] as $fixedRule): ?>
                -->
                            <div class="restriction fixed" data-restriction="<?php echo $fixedRule; ?>">
                                <span><?php echo $fixedRule; ?></span>
                                <input type="hidden" name="restriction[<?php echo $name; ?>][]"
                                       value="<?php echo $fixedRule; ?>">
                            </div><!--
              <?php endforeach; ?>

              <?php foreach ($this->restrictions[$name] as $restriction): ?>
                <?php if (!in_array($restriction, $restrictions['fixedRules'])): ?>
                  -->
                            <div class="restriction" data-restriction="<?php echo $restriction; ?>"><!--
                    --><span><?php echo $restriction; ?></span><a href="#">&times;</a><!--
                    --><input type="hidden" name="restriction[<?php echo $name; ?>][]"
                              value="<?php echo $restriction; ?>"><!--
                  --></div><!--
                <?php endif; ?>
              <?php endforeach; ?>
            --></div>
                        <a href="#" class="btn btn-small btn-primary" data-action="restriction">Add restriction</a>

                        <div class="actions" style="display: none;">
                            <select>
                                <?php foreach ($this->rules as $rule): ?>
                                    <option value="<?php echo $rule; ?>"><?php echo $rule; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <a href="#" class="btn btn-small btn-success" data-action="add">Add restriction</a>
                            <a href="#" class="btn btn-small btn-danger" data-action="cancel">Cancel</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <input type="hidden" name="controller" id="controller" value="security"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value="save"/>
</form>
