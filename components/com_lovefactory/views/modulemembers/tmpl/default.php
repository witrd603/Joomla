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

<!--[if lte IE 7]>
<style>
    .mod_lovefactory_latest .lovefactory-profile-thumbnail {
        width: < ? php echo $ this- > width;
        ? > px;
    }
</style>
<![endif]-->

<div class="mod_lovefactory_members<?php echo $this->moduleClass; ?> lovefactory-module lovefactory-view"
     id="lovefactory-module-<?php echo $this->moduleId; ?>">
    <?php if ($this->userFilterGenders): ?>
        <?php echo JHtml::_('ModuleConfiguration.ConfigurationLink', $this->moduleId); ?>
    <?php endif; ?>

    <div class="update">
        <?php echo $this->loadTemplate('items'); ?>
    </div>
</div>
