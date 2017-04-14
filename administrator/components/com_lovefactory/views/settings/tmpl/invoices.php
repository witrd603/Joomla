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

JHTML::_('stylesheet', 'main.css', 'components/com_lovefactory/assets/css/views/backend/'); ?>
<?php JHTML::_('behavior.tooltip'); ?>

<?php JToolBarHelper::title(JText::_('SETTINGS_PAGE_TITLE_INVOICES'), 'generic.png'); ?>

<?php JToolBarHelper::save(); ?>
<?php JToolBarHelper::apply(); ?>
<?php JToolBarHelper::cancel(); ?>

<style>
    .icon-48-generic {
        background-image: url(<?php echo JURI::root(); ?>components/com_lovefactory/assets/images/love.png);
    }

    td {
        vertical-align: top;
    }
</style>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

    <ul class="nav nav-tabs">
        <li class="<?php echo $this->activeTab == 'general' ? 'active' : ''; ?>"><a href="#general"
                                                                                    data-toggle="tab"><?php echo JText::_('SETTINGS_TAB_INVOCIES_GENERAL'); ?></a>
        </li>
        <li class="<?php echo $this->activeTab == 'buyer' ? 'active' : ''; ?>"><a href="#buyer"
                                                                                  data-toggle="tab"><?php echo JText::_('SETTINGS_TAB_INVOCIES_BUYER_SELLER'); ?></a>
        </li>
        <li class="<?php echo $this->activeTab == 'template' ? 'active' : ''; ?>"><a href="#template"
                                                                                     data-toggle="tab"><?php echo JText::_('SETTINGS_TAB_INVOCIES_TEMPLATE'); ?></a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane <?php echo $this->activeTab == 'general' ? 'active' : ''; ?>" id="general">
            <div class="row-fluid">
                <div class="span6">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_INVOICES_GENERAL'); ?></legend>
                        <?php require_once('_invoices_general.php'); ?>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="tab-pane <?php echo $this->activeTab == 'buyer' ? 'active' : ''; ?>" id="buyer">
            <div class="row-fluid">
                <div class="span6">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_INVOICES_SELLER'); ?></legend>
                        <?php require_once('_invoices_seller.php'); ?>
                    </fieldset>
                </div>

                <div class="span6">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_INVOICES_BUYER'); ?></legend>
                        <?php require_once('_invoices_buyer.php'); ?>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="tab-pane <?php echo $this->activeTab == 'template' ? 'active' : ''; ?>" id="template">
            <div class="row-fluid">
                <div class="span12">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_INVOICES_TEMPLATE'); ?></legend>
                        <?php require_once('_invoices_template.php'); ?>
                    </fieldset>
                </div>
            </div>
        </div>

    </div>

    <input type="hidden" name="controller" value="settings"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="layout" id="layout" value="invoices"/>
</form>
