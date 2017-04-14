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

<?php JToolBarHelper::title(JText::_('SETTINGS_PAGE_TITLE'), 'generic.png'); ?>

<?php JToolBarHelper::apply(); ?>
<?php JToolBarHelper::save(); ?>
<?php JToolBarHelper::cancel(); ?>

<style>
    td {
        vertical-align: top;
    }

    ul.adminformlist {
        padding: 0;
        margin: 0;
    }

    ul.adminformlist li {
        float: left;
        clear: both;
        list-style-type: none;
    }

    ul.adminformlist label {
        float: left;
        margin-top: 8px;
        margin-right: 5px;
    }

    #lovefactory-googlemap img {
        max-width: none;
    }
</style>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

    <ul class="nav nav-tabs">
        <li class="<?php echo $this->activeTab == 'general' ? 'active' : ''; ?>"><a href="#general"
                                                                                    data-toggle="tab"><?php echo JText::_('SETTINGS_TAB_GENERAL'); ?></a>
        </li>
        <li class="<?php echo $this->activeTab == 'page-settings' ? 'active' : ''; ?>"><a href="#page-settings"
                                                                                          data-toggle="tab"><?php echo JText::_('SETTINGS_TAB_PAGE_SETTIGNS'); ?></a>
        </li>
        <li class="<?php echo $this->activeTab == 'interactions' ? 'active' : ''; ?>"><a href="#interactions"
                                                                                         data-toggle="tab"><?php echo JText::_('SETTINGS_TAB_INTERACTIONS'); ?></a>
        </li>
        <li class="<?php echo $this->activeTab == 'infobar' ? 'active' : ''; ?>"><a href="#infobar"
                                                                                    data-toggle="tab"><?php echo JText::_('SETTINGS_TAB_INFOBAR_SHOUTBOX'); ?></a>
        </li>
        <li class="<?php echo $this->activeTab == 'integrations' ? 'active' : ''; ?>"><a href="#integrations"
                                                                                         data-toggle="tab"><?php echo JText::_('SETTINGS_TAB_INTEGRATIONS'); ?></a>
        </li>
        <li class="<?php echo $this->activeTab == 'banned-words' ? 'active' : ''; ?>"><a href="#banned-words"
                                                                                         data-toggle="tab"><?php echo JText::_('SETTINGS_TAB_BANNED_WORDS'); ?></a>
        </li>
        <li class="<?php echo $this->activeTab == 'cron' ? 'active' : ''; ?>"><a href="#cron"
                                                                                 data-toggle="tab"><?php echo JText::_('SETTINGS_TAB_CRON_JOBS'); ?></a>
        </li>
        <li class="<?php echo $this->activeTab == 'sysinfo' ? 'active' : ''; ?>"><a href="#sysinfo"
                                                                                    data-toggle="tab"><?php echo JText::_('SETTINGS_TAB_SYS_INFO'); ?></a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane <?php echo $this->activeTab == 'general' ? 'active' : ''; ?>" id="general">
            <div class="row-fluid">
                <div class="span6">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_STATUS'); ?></legend>
                        <?php echo $this->loadTemplate('status'); ?>
                    </fieldset>

                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_GENERAL'); ?></legend>
                        <?php require_once('_general.php'); ?>
                    </fieldset>

                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_REGISTRATION'); ?></legend>
                        <?php require_once('_general_registration.php'); ?>
                    </fieldset>

                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_PROFILE_FILLIN_REMINDER'); ?></legend>
                        <?php require_once('_general_profile_fillin_reminder.php'); ?>
                    </fieldset>
                </div>
                <div class="span6">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_GROUPS'); ?></legend>
                        <?php require_once('_general_groups.php'); ?>
                    </fieldset>

                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_APPROVAL'); ?></legend>
                        <?php require_once('_general_approval.php'); ?>
                    </fieldset>

                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_PHOTOS'); ?></legend>
                        <?php require_once('_photos.php'); ?>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="tab-pane <?php echo $this->activeTab == 'page-settings' ? 'active' : ''; ?>" id="page-settings">
            <div class="row-fluid">
                <div class="span6">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_PROFILE_VIEW_PAGE'); ?></legend>
                        <?php require_once('_page_view.php'); ?>
                    </fieldset>

                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_PROFILE_SETTINGS_PAGE'); ?></legend>
                        <?php require_once('_page_profile_settings.php'); ?>
                    </fieldset>

                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_WALL_PAGE'); ?></legend>
                        <?php require_once('_page_wall.php'); ?>
                    </fieldset>

                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_MEMBERS_MAP_PAGE'); ?></legend>
                        <?php require_once('_page_members_map.php'); ?>
                    </fieldset>
                </div>

                <div class="span6">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_CREATE_PROFILE'); ?></legend>
                        <?php require_once('_page_createprofile.php'); ?>
                    </fieldset>

                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_SEARCH_PAGE'); ?></legend>
                        <?php require_once('_page_search.php'); ?>
                    </fieldset>

                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_SEARCH_RADIUS_PAGE'); ?></legend>
                        <?php require_once('_page_search_radius.php'); ?>
                    </fieldset>

                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_VIDEOS_GALLERY_PAGE'); ?></legend>
                        <?php require_once('_page_videos.php'); ?>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="tab-pane <?php echo $this->activeTab == 'interactions' ? 'active' : ''; ?>" id="interactions">
            <div class="row-fluid">
                <div class="span6">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_GENERAL'); ?></legend>
                        <?php require_once('_interactions_general.php'); ?>
                    </fieldset>

                    <fieldset class="adminform interactions_related">
                        <legend><?php echo JText::_('SETTINGS_TAB_INTERACTIONS'); ?></legend>
                        <?php require_once('_interactions_interactions.php'); ?>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="tab-pane <?php echo $this->activeTab == 'infobar' ? 'active' : ''; ?>" id="infobar">
            <div class="row-fluid">
                <div class="span6">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_INFOBAR'); ?></legend>
                        <?php echo $this->form->renderFieldset('infobar'); ?>
                    </fieldset>
                </div>

                <div class="span6">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_SHOUTBOX'); ?></legend>
                        <?php require_once('_general_shoutbox.php'); ?>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="tab-pane <?php echo $this->activeTab == 'integrations' ? 'active' : ''; ?>" id="integrations">
            <div class="row-fluid">
                <div class="span6">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTIGNS_TAB_CHAT_FACTORY'); ?></legend>
                        <?php require_once('_integration_chatfactory.php'); ?>
                    </fieldset>

                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTIGNS_TAB_BLOG_FACTORY'); ?></legend>
                        <?php require_once('_integration_blogfactory.php'); ?>
                    </fieldset>

                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_RECAPTCHA'); ?></legend>
                        <?php require_once('_integration_recaptcha.php'); ?>
                    </fieldset>

                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_YOUTUBE'); ?></legend>
                        <?php require_once('_integration_youtube.php'); ?>
                    </fieldset>
                </div>

                <div class="span6">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_GRAVATAR'); ?></legend>
                        <?php require_once('_integration_gravatar.php'); ?>
                    </fieldset>

                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTIGNS_TAB_GOOGLE_MAPS'); ?></legend>
                        <?php require_once('_gmaps_general.php'); ?>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="tab-pane <?php echo $this->activeTab == 'banned-words' ? 'active' : ''; ?>" id="banned-words">
            <div class="row-fluid">
                <div class="span6">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_GENERAL'); ?></legend>
                        <?php require_once('_banned_words_general.php'); ?>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="tab-pane <?php echo $this->activeTab == 'cron' ? 'active' : ''; ?>" id="cron">
            <div class="row-fluid">
                <div class="span6">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_GENERAL'); ?></legend>
                        <?php require_once('_general_cron.php'); ?>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="tab-pane <?php echo $this->activeTab == 'sysinfo' ? 'active' : ''; ?>" id="sysinfo">
            <div class="row-fluid">
                <div class="span6">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_SYS_INFO_JOOMLA'); ?></legend>
                        <?php echo $this->loadTemplate('joomla'); ?>
                    </fieldset>
                </div>

                <div class="span6">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('SETTINGS_TAB_SYS_INFO_PHP'); ?></legend>
                        <?php echo $this->loadTemplate('php'); ?>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="controller" value="settings"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value=""/>
</form>
