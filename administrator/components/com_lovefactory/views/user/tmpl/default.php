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

<script>
    Joomla.submitbutton = function (pressbutton) {
        var split = pressbutton.split('.');

        document.getElementById('controller').value = split[0];
        pressbutton = split[1];

        Joomla.submitform(pressbutton);
    }
</script>

<style>
    .render-profile .form-horizontal .controls {
        margin-left: 0;
    }

    .render-profile label.control-label,
    .render-profile .form-horizontal .control-label {
        display: block;
        font-weight: bold;
        float: none;
        margin-top: 0;
    }

    .render-profile .form-horizontal div.control-group {
        margin-bottom: 0;
    }

    .render-profile legend {
        margin: 10px 0;
        font-size: 14px;
        font-weight: bold;
        line-height: normal;
    }

    .render-profile ul.field-info li:empty {
        display: none;
    }

    .render-profile select {
        width: auto;
    }

    .render-profile textarea {
        width: 100%;
        box-sizing: border-box;
    }

    .render-profile div.profile-actions {
        margin: 5px 0;
        padding: 5px;
        background-color: #eeeeee;
    }

    .render-profile div.field-error {
        color: #ff0000;
    }

    .render-profile div.lovefactory-thumbnail {
        background-repeat: no-repeat;
        background-position: center center;
    }
</style>

<form
    action="<?php echo JRoute::_('index.php?option=com_lovefactory&view=user&user_id=' . (int)$this->item->user_id); ?>"
    method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">

    <div class="width-40 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('JDETAILS'); ?></legend>

            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('details') as $field): ?>
                    <li><?php echo $field->label; ?>
                        <?php echo $field->input; ?></li>
                <?php endforeach; ?>
            </ul>

            <div class="clr"></div>
        </fieldset>
    </div>

    <div class="width-60 fltrt">
        <?php echo JHtml::_('sliders.start', 'user-sliders-' . $this->item->user_id, array('useCookie' => 1)); ?>
        <?php echo JHtml::_('sliders.panel', FactoryText::_('user_panel_profile'), 'profile'); ?>
        <fieldset class="panelform render-profile">
            <?php echo $this->loadTemplate($this->mode); ?>
        </fieldset>

        <?php echo JHtml::_('sliders.panel', FactoryText::_('user_panel_membership'), 'membership'); ?>
        <?php echo $this->loadTemplate('membership'); ?>
        <?php echo JHtml::_('sliders.panel', FactoryText::_('user_panel_photos'), 'photos'); ?>
        <?php echo $this->loadTemplate('list_photos'); ?>
        <?php echo JHtml::_('sliders.panel', FactoryText::_('user_panel_videos'), 'videos'); ?>
        <?php echo $this->loadTemplate('list_videos'); ?>
        <?php echo JHtml::_('sliders.panel', FactoryText::_('user_panel_ip'), 'ip'); ?>
        <?php echo $this->loadTemplate('list_ips'); ?>
        <?php echo JHtml::_('sliders.panel', FactoryText::_('user_panel_memberships'), 'memberships'); ?>
        <?php echo $this->loadTemplate('list_memberships'); ?>

        <?php echo JHtml::_('sliders.end'); ?>
    </div>

    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="controller" id="controller" value=""/>
    <?php echo JHtml::_('form.token'); ?>

    <div class="clr"></div>
</form>
