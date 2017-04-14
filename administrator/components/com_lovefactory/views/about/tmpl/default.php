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

<style type="text/css">
    .icon-48-generic {
        background-image: url(<?php echo JURI::root(); ?>components/com_lovefactory/assets/images/love.png);
    }
</style>

<style type="text/css">
    .about-factory {
        padding: 20px;
    }

    .about-factory h1 {
        padding: 0 0 10px 0 !important;
        margin: 0 !important;
    }

    .about-factory .text {
        margin-bottom: 30px;
    }

    .about-factory .version-label {
        width: 140px;
        display: inline-block;
        margin-bottom: 5px;
    }

    .about-factory .fb-like {
        display: inline-block;
    }
</style>

<script type="text/javascript">
    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook - jssdk'));
</script>

<div class="about-factory">
    <h1>Latest Release Notes</h1>
    <div class="text">
        <div style="width: 200px; display: inline-block;">
            <div style="margin: 0 0 10px 0;"><b>
                    <?php if ($this->about->newVersion): ?>
                        <span style="color: red; text-decoration: blink;">New version available!</span>
                    <?php else: ?>
                        No new version available!
                    <?php endif; ?>
                </b></div>

            <div>
                <div class="version-label">Your installed version:</div>
                <b><?php echo $this->about->currentVersion; ?></b></div>
            <div>
                <div class="version-label">Latest version available:</div>
                <b><?php echo $this->about->latestVersion; ?></b></div>
        </div>

        <div class="fb-like" data-href="https://www.facebook.com/theFactoryJoomla" data-send="false"
             data-layout="box_count" data-width="450" data-show-faces="false"></div>

        <div style="margin-top: 10px;"><?php echo $this->about->versionHistory; ?></div>
    </div>

    <h1>Support and Updates</h1>
    <div class="text"><?php echo $this->about->downloadLink; ?></div>

    <h1>Other Products</h1>
    <div class="text"><?php echo $this->about->otherProducts; ?></div>

    <h1>About thePHPfactory</h1>
    <div class="text"><?php echo $this->about->aboutFactory; ?></div>
</div>
