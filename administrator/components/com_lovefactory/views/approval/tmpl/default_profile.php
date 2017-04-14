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

<fieldset class="panelform render-profile">
    <?php echo $this->item; ?>
</fieldset>
