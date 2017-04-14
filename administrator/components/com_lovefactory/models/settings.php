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

jimport('joomla.application.component.model');

class BackendModelSettings extends JModelLegacy
{
    var $settings;
    var $file;
    var $variables;

    function __construct()
    {
        $this->file = JPATH_COMPONENT_ADMINISTRATOR . DS . 'settings.php';

        //require_once($this->file);
        $this->settings = new LovefactorySettings();

        $this->variables = array(
            'opposite_gender_search' => 'integer',
            'opposite_gender_display' => 'integer',

            'currency_symbol' => 'integer',
            'require_fillin' => 'integer',

            'bootstrap_template' => 'integer',
            'registration_membership' => 'integer',
            'restrict_default_membership' => 'integer',

            'members_map_show_profile_event' => 'string',
            'members_map_grouped_members_display' => 'string',

            'friendship_requests_limit' => 'integer',
            'friendship_request_message' => 'integer',

            'location_field_gmap_field' => 'integer',
            'registration_fields_mapping_username' => 'integer',
            'registration_fields_mapping_email' => 'integer',
            'registration_fields_mapping_password' => 'integer',
            'registration_fields_mapping_name' => 'integer',

            'invoice_vat_rate' => 'integer',
            'photo_max_width' => 'integer',
            'photo_max_height' => 'integer',
            'photos_storage_mode' => 'integer',
            'photos_max_size' => 'integer',
            'thumbnail_max_height' => 'integer',
            'thumbnail_max_width' => 'integer',
            'enable_comments' => 'integer',
            'enable_messages' => 'integer',
            'enable_rating' => 'integer',
            'enable_rating_update' => 'integer',
            'currency' => 'string',
            'enable_wallpage' => 'integer',
            'wallpage_entries' => 'integer',
            'my_gallery_action_links' => 'integer',
            'show_translation_fields' => 'integer',
            'enable_swfupload_debug' => 'integer',
            'enable_classic_uploader' => 'integer',
            'enable_friends' => 'integer',
            'search_jump_to_results' => 'integer',
            'enable_default_infobar' => 'integer',
            'remove_ratings_on_profile_remove' => 'integer',

            'approval_photos' => 'integer',
            'approval_videos' => 'integer',
            'approval_comments' => 'integer',
            'approval_comments_photo' => 'integer',
            'approval_comments_video' => 'integer',
            'approval_messages' => 'integer',
            'approval_groups' => 'integer',
            'approval_group_threads' => 'integer',
            'approval_groups_posts' => 'integer',
            'approval_profile' => 'integer',

            'enable_invoices' => 'integer',
            'invoice_template' => 'notification',
            'invoice_template_seller' => 'notification',
            'invoice_template_buyer' => 'notification',

            'enable_infobar' => 'integer',
            'infobar_location' => 'integer',
            'infobar_refresh_interval' => 'integer',
            'enable_infobar_interactions' => 'integer',
            'infobar_interactions_itemid' => 'integer',
            'enable_infobar_messages' => 'integer',
            'infobar_messages_itemid' => 'integer',
            'enable_infobar_requests' => 'integer',
            'infobar_requests_itemid' => 'integer',
            'enable_infobar_comments' => 'integer',
            'infobar_comments_itemid' => 'integer',
            'enable_infobar_view_profile' => 'integer',
            'infobar_view_profile_itemid' => 'integer',
            'enable_infobar_update_profile' => 'integer',
            'infobar_update_profile_itemid' => 'integer',
            'enable_infobar_gallery' => 'integer',
            'infobar_gallery_itemid' => 'integer',
            'enable_infobar_friends' => 'integer',
            'infobar_friends_itemid' => 'integer',
            'enable_infobar_close' => 'integer',
            'enable_infobar_logout' => 'integer',
            'infobar_show_labels' => 'integer',
            'cron_job_profile_visitors' => 'integer',

            'jump_to_results' => 'integer',
            'sort_by_membership' => 'integer',
            'results_columns' => 'integer',
            'display_hidden' => 'integer',
            'gender_pricing' => 'integer',
            'gender_change' => 'integer',
            'profile_status_change' => 'integer',
            'fields_location' => 'integer',
            'update_fields_location' => 'integer',
            'enable_relationships' => 'integer',
            'invalid_membership_action' => 'integer',
            'enable_top_friends' => 'integer',
            'results_default_sort_order' => 'integer',
            'results_default_sort_by' => 'integer',
            'profile_link_new_window' => 'integer',

            'enable_groups' => 'integer',
            'groups_allow_users_create' => 'integer',
            'groups_post_allowed_html' => 'string',
            'groups_photo_max_width' => 'integer',
            'groups_photo_max_height' => 'integer',
            'groups_list_limit' => 'integer',
            'group_posts_list_limit' => 'integer',
            'members_map_profile_new_link' => 'integer',
            'search_radius_profile_new_link' => 'integer',

            'enable_token_auth' => 'integer',
            'delete_user_plugin' => 'integer',

            'admin_comments_delete' => 'integer',
            'user_comments_delete' => 'integer',
            'enable_banned_words_filter' => 'integer',

            'date_format' => 'string',
            'date_custom_format' => 'string',

            'enable_shoutbox' => 'integer',
            'shoutbox_refresh_interval' => 'integer',
            'shoutbox_messages' => 'integer',
            'cron_job_shoutbox_messages' => 'integer',
            'shoutbox_log' => 'integer',

            'user_delete_comments' => 'integer',
            'user_delete_photo_comments' => 'integer',
            'user_delete_video_comments' => 'integer',
            'user_delete_profile_visits' => 'integer',
            'user_delete_ratings' => 'integer',
            'user_delete_shoutbox' => 'integer',
            'user_delete_interactions' => 'integer',
            'user_delete_payments' => 'integer',
            'user_delete_actions' => 'integer',
            'user_delete_created_groups' => 'integer',
            'user_delete_posts_in_groups' => 'integer',

            'enable_gravatar_integration' => 'integer',
            'enable_chatfactory_integration' => 'integer',
            'chatfactory_integration_users_list' => 'integer',
            'chatfactory_integration_delete_user' => 'integer',
            'enable_blogfactory_integration' => 'integer',

            'enable_youtube_integration' => 'integer',
            'youtube_api_key' => 'string',

            'enable_gmaps' => 'integer',
            'gmaps_api_key' => 'string',
            'gmaps_default_x' => 'string',
            'gmaps_default_y' => 'string',
            'gmaps_default_z' => 'integer',
            'location_field_city' => 'integer',
            'location_field_country' => 'integer',
            'location_field_state' => 'integer',

            'videos_pagination_limit' => 'integer',
            'videos_list_pagination_limit' => 'integer',
            'videos_comments_pagination_limit' => 'integer',
            'videos_embed_allowed_html' => 'string',

            'search_radius_group_users' => 'integer',
            'search_radius_group_zoom' => 'integer',
            'search_radius_default_membership_show' => 'integer',
            'search_default_membership_show' => 'integer',

            'recaptcha_public_key' => 'string',
            'recaptcha_private_key' => 'string',
            'enable_recaptcha' => 'integer',

            'enable_profile_friends' => 'integer',
            'profile_friends_number' => 'integer',
            'profile_friends_sort' => 'integer',
            'profile_friends_top' => 'integer',

            'limit_search_results' => 'integer',
            'distances_unit' => 'integer',
            'max_search_radius' => 'integer',
            'enable_search_radius' => 'integer',
            'enable_search_radius_sex_filter' => 'integer',
            'allow_guest_search_radius' => 'integer',
            'enable_members_map' => 'integer',
            'allow_guest_members_map' => 'integer',
            'members_map_group_users' => 'integer',
            'members_map_group_zoom' => 'integer',
            'members_map_default_membership_show' => 'integer',
            'members_map_gmap_field' => 'integer',
            'search_radius_gmap_field' => 'integer',

            'enable_status' => 'integer',
            'status_max_length' => 'integer',
            'registration_mode' => 'integer',
            'enable_profile_fillin' => 'integer',
            'registration_login_redirect' => 'integer',

            'hide_banned_profiles' => 'integer',
            'hide_ignored_profiles' => 'integer',
            'default_photo_extension' => 'string',
            'enable_smarty' => 'integer',
            'number_search_results_per_page' => 'integer',
            'allow_guests_view_profile' => 'integer',
            'end_membership_notification' => 'integer',
            'cron_password' => 'string',
            'end_membership_notify_interval' => 'string',
            'enable_interactions' => 'integer',
            'enable_interaction_kiss' => 'integer',
            'enable_interaction_wink' => 'integer',
            'enable_interaction_hug' => 'integer',

            'wallpage_add_status' => 'integer',
            'wallpage_add_photo' => 'integer',
            'wallpage_add_rating' => 'integer',
            'wallpage_add_comment' => 'integer',
            'wallpage_add_photo_comment' => 'integer',
            'wallpage_add_video_comment' => 'integer',

            'wallpage_add_video' => 'integer',
            'wallpage_add_friend' => 'integer',
            'wallpage_add_relationship' => 'integer',
            'wallpage_create_group' => 'integer',
            'wallpage_join_group' => 'integer',

            'html_notifications' => 'integer',

            'notification_new_comment_enabled' => 'integer',
            'notification_new_photo_comment_enabled' => 'integer',
            'notification_change_membership_enabled' => 'integer',
            'notification_change_membership_receivers' => 'array',
            'notification_new_interaction_enabled' => 'integer',
            'notification_new_message_enabled' => 'integer',
            'notification_new_rating_enabled' => 'integer',
            'cron_job_wallpage_entries_interval' => 'integer',

            'default_membership_access' => 'array',

            'profile_fillin_reminder_enable' => 'integer',
            'profile_fillin_reminder_interval' => 'integer',

            'display_user_name' => 'array',

            'create_profile_admin_groups' => 'array',
        );

        parent::__construct();
    }

    function store()
    {
        $displayName = $this->settings->display_user_name;

        $this->saveBannedWords();
        $this->updateSettings();
        $this->writeSettings();

        $data = JFactory::getApplication()->input->get('settings', array(), 'array');
        $this->save($data);

        // Update all display names.
        if ($displayName !== $this->settings->display_user_name) {
            $model = JModelLegacy::getInstance('User', 'BackendModel');
            $model->updateDisplayName($this->settings->display_user_name[0], $this->settings->display_user_name[1]);
        }

        return true;
    }

    function updateSettings()
    {
        $old_photos_storage_mode = $this->settings->photos_storage_mode;

        $post = JFilterInput::getInstance()->clean($_POST, null);

        $this->_prepareData($post);

        foreach ($this->variables as $setting => $type) {
            $this->updateSettingsValue($setting, $type, $this->settings->$setting);
        }

        $this->settings->currency = strtoupper($this->settings->currency);

        if ($old_photos_storage_mode != $this->settings->photos_storage_mode) {
            $this->movePhotos($this->settings->photos_storage_mode);
        }
    }

    protected function updateSettingsValue($setting, $type, $default)
    {
        switch ($type) {
            case 'string':
            case 'integer':
                $this->settings->$setting = addslashes(JFactory::getApplication()->input->get($setting, $default, $type));
                break;

            case 'notification':
                $this->settings->$setting = addslashes(JFactory::getApplication()->input->getRaw($setting, $default));
                break;

            case 'array':
                $this->settings->$setting = JFactory::getApplication()->input->get($setting, $default, $type);
                break;
        }
    }

    function writeSettings()
    {
        $handle = fopen($this->file, 'w');
        fwrite($handle, $this->templateSettings());
        fclose($handle);
    }

    function templateSettings()
    {
        $max_length = 0;
        foreach ($this->variables as $setting => $type) {
            $max_length = (strlen($setting) > $max_length) ? strlen($setting) : $max_length;
        }

        $template = '<?php

defined(\'_JEXEC\') or die(\'Restricted access\');

class LovefactorySettings
{';

        foreach ($this->variables as $setting => $type) {
            $template .= "\n";
            $template .=
                '  var $' . $setting . str_repeat(' ', $max_length - strlen($setting)) . ' = ';

            switch ($type) {
                case 'string':
                case 'notification':
                    $template .= '"' . $this->settings->$setting . '"';
                    break;

                case 'integer':
                    $template .= intval($this->settings->$setting);
                    break;

                case 'array':
                    if (count($this->settings->$setting)) {
                        $template .= 'array(\'' . implode('\',\'', $this->settings->$setting) . '\')';
                    } else {
                        $template .= 'array()';
                    }
                    break;
            }

            $template .= ';';
        }
        $template .= "\n";
        $template .=
            '}';

        return $template;
    }

    function restoreBackup($backup_settings)
    {
        $this->updateSettings();

        foreach ($this->variables as $setting => $type) {
            if (isset($backup_settings->$setting)) {
                JFactory::getApplication()->input->set($setting, $backup_settings->$setting);
                $this->updateSettingsValue($setting, $type, $backup_settings->$setting);
            }
        }

        if (isset($backup_settings->terms_and_conditions)) {
            $this->settings->terms_and_conditions = $backup_settings->terms_and_conditions;
        }

        $this->writeSettings();

        return true;
    }

    function getAdmins()
    {
        $admins = $this->getGroupsForAccessRule('core.login.admin');
        $superadmins = $this->getGroupsForAccessRule('core.admin');
        $groups = array_merge($admins, $superadmins);

        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('u.id AS id, u.username')
            ->from('#__users AS u')
            ->leftJoin('#__user_usergroup_map m ON m.user_id = u.id')
            ->where('m.group_id IN (' . implode(',', $groups) . ')')
            ->order('u.username ASC');

        $db->setQuery($query);

        return $db->loadObjectList();
    }

    function getAccess()
    {
        $array = array(
            'advancedsearch' => 'Advanced search',
            'blacklist' => 'Ignore list',
            'comment' => 'Comment (add, delete, report)',
            //'edit'           => 'Profile edit',
            'friend' => 'Friendship (request, accept, remove, cancel, reject)',
            'friends' => 'Friends list',
            'friendspending' => 'Pending friendships requests list',
            'gallery' => 'Gallery',
            'inbox' => 'Inbox',
            'interaction' => 'Interaction (send, respond)',
            'interactions' => 'Interactions list',
            'mailbox' => 'Mailbox (empty inbox, empty outbox)',
            'membersmap' => 'Members map',
            'message' => 'Message (send, delete, report)',
            'messageread' => 'Read message',
            'messagewrite' => 'Write message',
            'myfriends' => 'My friends list',
            'mygallery' => 'My gallery',
            'online' => 'Online users list',
            'outbox' => 'Outbox',
            'otherprofiles' => 'Other profiles',
            'rating' => 'Rating (add)',
            'radiussearch' => 'Radius search',
            'quicksearch' => 'Quick search',
            'status' => 'Status',
            'wallpage' => 'Detailed wallpage',
        );

        return $array;
    }

    function getShoutboxLogSize()
    {
        $log = JPATH_COMPONENT_ADMINISTRATOR . DS . 'shoutbox_log.txt';

        return number_format(filesize($log) / 1024, 2) . ' MB';
    }

    function getSettingsFileIsWritable()
    {
        return is_writable(JPATH_COMPONENT_ADMINISTRATOR . DS . 'settings.php');
    }

    public function getChatFactory()
    {
        return $this->getComponentInstalled('com_chatfactory');
    }

    public function getBlogFactory()
    {
        return $this->getComponentInstalled('com_blogfactory');
    }

    protected function getComponentInstalled($element = null)
    {
        $extension = JTable::getInstance('extension');

        return $extension->find(array('type' => 'component', 'element' => $element));
    }

    function _prepareData($post)
    {
        // Default access membership
        if (!isset($post['default_membership_access'])) {
            return false;
        }

        $array = JFactory::getApplication()->input->get('default_membership_access', array(), 'array');
        JFactory::getApplication()->input->set('default_membership_access', array_keys($array));
    }

    public function getBannedWords()
    {
        $src = JPATH_COMPONENT_ADMINISTRATOR . DS . 'banned_words.php';
        require_once($src);

        return implode("\n", $banned_words);
    }

    protected function saveBannedWords()
    {
        jimport('joomla.filesystem.file');

        $words = JFactory::getApplication()->input->getString('banned_words');
        $src = JPATH_COMPONENT_ADMINISTRATOR . DS . 'banned_words.php';

        $buffer = '<?php $banned_words = array(';

        foreach (explode("\n", $words) as $word) {
            $word = trim($word);
            if ('' == $word) {
                continue;
            }

            $buffer .= "'" . addslashes($word) . "'," . "\n";
        }

        $buffer .= ');';

        JFile::write($src, $buffer);
    }

    private function getGroupsForAccessRule($access = 'core.login.admin')
    {
        jimport('joomla.database.tablenested');

        $groups = JAccess::getAssetRules(1)->getData();
        $groups = $groups[$access]->getData();

        $usergroup = new JTableNested('#__usergroups', 'id', JFactory::getDbo());

        $usergroup->load(1);
        $tree = $usergroup->getTree();

        $array = array();

        foreach ($tree as $leaf) {
            $array[$leaf->id] = $leaf;
        }

        $tree = $array;
        $array = array();

        foreach ($groups as $id => $access) {
            $current = $tree[$id];

            foreach ($tree as $leaf) {
                if ($leaf->lft >= $current->lft &&
                    $leaf->rgt <= $current->rgt
                ) {
                    if (!$access && isset($array[$leaf->id])) {
                        unset($array[$leaf->id]);
                    }

                    if ($access && !isset($array[$leaf->id])) {
                        $array[$leaf->id] = $access;
                    }
                }
            }
        }

        return array_keys($array);
    }

    public function getNotifications()
    {
        return array();
        $dbo = JFactory::getDbo();
        $table = $this->getTable('Notification');
        $types = $table->getTypes();
        $notifications = array();

        $query = $dbo->getQuery(true)
            ->select('n.lang_code, n.type_id')
            ->from('#__lovefactory_notifications n')
            ->order('n.lang_code ASC');
        $dbo->setQuery($query);
        $items = $dbo->loadObjectList();

        foreach ($types as $id => $type) {
            $notifications[$id] = array('name' => $type, 'notifications' => array());
        }

        foreach ($items as $item) {
            $item->lang_code = '*' == $item->lang_code ? JText::_('All') : $item->lang_code;
            $notifications[$item->type_id]['notifications'][] = $item;
        }

        return $notifications;
    }

    public function getPluginsStatus()
    {
        $plugins = array();

        // Check the System plugin
        $extension = $this->getTable('Extension', 'JTable');
        $result = $extension->find(array('type' => 'plugin', 'element' => 'lovefactory', 'folder' => 'system'));

        if (!$result) {
            $plugins['system'] = 0;
        } else {
            $extension->load($result);

            $plugins['system'] = $extension->enabled ? 1 : 2;
        }

        // Check the User plugin
        $extension = $this->getTable('Extension', 'JTable');
        $result = $extension->find(array('type' => 'plugin', 'element' => 'lovefactoryuser', 'folder' => 'user'));

        if (!$result) {
            $plugins['user'] = 0;
        } else {
            $extension->load($result);

            $plugins['user'] = $extension->enabled ? 1 : 2;
        }

        return $plugins;
    }

    public function getLocationFields()
    {
        $array = array('Text', 'Textarea', 'Select', 'Radio');

        return $this->getFieldsList($array);
    }

    public function getGoogleMapsFields()
    {
        $array = array('GoogleMapsLocation');

        return $this->getFieldsList($array);
    }

    public function getUsernameFields()
    {
        $array = array('username');

        return $this->getFieldsList($array);
    }

    public function getEmailFields()
    {
        $array = array('email');

        return $this->getFieldsList($array);
    }

    public function getPasswordFields()
    {
        $array = array('password');

        return $this->getFieldsList($array);
    }

    public function getNameFields()
    {
        $array = array('text');

        return $this->getFieldsList($array);
    }

    protected function getFieldsList($filter)
    {
        $dbo = JFactory::getDbo();

        foreach ($filter as &$item) {
            $item = $dbo->quote(strtoupper($item));
        }

        $query = $dbo->getQuery(true)
            ->select('f.id AS value, f.title AS text')
            ->from('#__lovefactory_fields f')
            ->where('UPPER(f.type) IN (' . implode(', ', $filter) . ')')
            ->where('f.published = 1');
        $dbo->setQuery($query);

        $fields = $dbo->loadObjectList();
        array_unshift($fields, JHtml::_('select.option', '', ''));

        return $fields;
    }

    public function getRedirectItems()
    {
        require_once realpath(JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');

        $items = MenusHelper::getMenuLinks('', 0, 0, '');
        $groups = array();

        // Build the groups arrays.
        foreach ($items as $menu) {
            // Initialize the group.
            $groups[$menu->menutype] = array();

            // Build the options array.
            foreach ($menu->links as $link) {
                $groups[$menu->menutype][] = JHtml::_('select.option', $link->value, $link->text, 'value', 'text', in_array($link->type, array('separator')));
            }
        }

        $groups = array_merge(array(array(0 => JText::_('SETTINGS_REGISTRATION_LOGIN_REDIRECT_DEFAULT'))), $groups);

        return JHtml::_(
            'select.groupedlist',
            $groups,
            'registration_login_redirect',
            array('list.attr' => '', 'id' => 'registration_login_redirect', 'list.select' => $this->settings->registration_login_redirect, 'group.items' => null, 'option.key.toHtml' => false, 'option.text.toHtml' => false));
    }

    public function getEditor()
    {
        $editor = JFactory::getApplication()->get('editor');

        return JEditor::getInstance($editor);
    }

    protected function movePhotos($new_mode)
    {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');

        $dbo = $this->getDbo();
        $app = LoveFactoryApplication::getInstance();
        $users = array();

        $old_mode = 1 == $new_mode ? 2 : 1;

        // 1. Move photos
        $query = $dbo->getQuery(true)
            ->select('p.*')
            ->from('#__lovefactory_photos p');
        $results = $dbo->setQuery($query)
            ->loadObjectList();

        foreach ($results as $result) {
            if (!JFolder::exists($app->getUserFolder($result->user_id, false, $new_mode))) {
                JFolder::create($app->getUserFolder($result->user_id, false, $new_mode));
            }

            $src = $app->getUserFolder($result->user_id, false, $old_mode) . DS . $result->filename;
            $dest = $app->getUserFolder($result->user_id, false, $new_mode) . DS . $result->filename;

            if (JFile::exists($src)) {
                JFile::move($src, $dest);
            }

            $src = $app->getUserFolder($result->user_id, false, $old_mode) . DS . 'thumb_' . $result->filename;
            $dest = $app->getUserFolder($result->user_id, false, $new_mode) . DS . 'thumb_' . $result->filename;

            if (JFile::exists($src)) {
                JFile::move($src, $dest);
            }

            if (!in_array($result->user_id, $users)) {
                $users[] = $result->user_id;
            }
        }

        // 2. Move video thumbnails
        $query = $dbo->getQuery(true)
            ->select('v.*')
            ->from('#__lovefactory_videos v');
        $results = $dbo->setQuery($query)
            ->loadObjectList();

        foreach ($results as $result) {
            if (!JFolder::exists($app->getUserFolder($result->user_id, false, $new_mode))) {
                JFolder::create($app->getUserFolder($result->user_id, false, $new_mode));
            }

            $src = $app->getUserFolder($result->user_id, false, $old_mode) . DS . $result->thumbnail;
            $dest = $app->getUserFolder($result->user_id, false, $new_mode) . DS . $result->thumbnail;

            if (JFile::exists($src)) {
                JFile::move($src, $dest);
            }

            if (!in_array($result->user_id, $users)) {
                $users[] = $result->user_id;
            }
        }

        // 3. Move group thumbnails
        $query = $dbo->getQuery(true)
            ->select('g.*')
            ->from('#__lovefactory_groups g');
        $results = $dbo->setQuery($query)
            ->loadObjectList();

        foreach ($results as $result) {
            if (!JFolder::exists($app->getUserFolder($result->user_id, false, $new_mode))) {
                JFolder::create($app->getUserFolder($result->user_id, false, $new_mode));
            }

            $src = $app->getUserFolder($result->user_id, false, $old_mode) . DS . 'group_' . $result->thumbnail;
            $dest = $app->getUserFolder($result->user_id, false, $new_mode) . DS . 'group_' . $result->thumbnail;

            if (JFile::exists($src)) {
                JFile::move($src, $dest);
            }

            $src = $app->getUserFolder($result->user_id, false, $old_mode) . DS . 'group_thumb_' . $result->thumbnail;
            $dest = $app->getUserFolder($result->user_id, false, $new_mode) . DS . 'group_thumb_' . $result->thumbnail;

            if (JFile::exists($src)) {
                JFile::move($src, $dest);
            }

            if (!in_array($result->user_id, $users)) {
                $users[] = $result->user_id;
            }
        }

        // 4. If old mode was per user folder, delete the user folders
        if (1 == $old_mode) {
            foreach ($users as $user) {
                if (JFolder::exists($app->getUserFolder($user, false, $old_mode))) {
                    JFolder::delete($app->getUserFolder($user, false, $old_mode));
                }
            }
        }

        return true;
    }

    public function getErrorReporting()
    {
        $config = new JConfig();
        $data = JArrayHelper::fromObject($config);

        $language = JFactory::getLanguage();
        $language->load('com_config', JPATH_ADMINISTRATOR);

        $options = array(
            'default' => JText::_('COM_CONFIG_FIELD_VALUE_SYSTEM_DEFAULT'),
            'none' => JText::_('COM_CONFIG_FIELD_VALUE_NONE'),
            'simple' => JText::_('COM_CONFIG_FIELD_VALUE_SIMPLE'),
            'maximum' => JText::_('COM_CONFIG_FIELD_VALUE_MAXIMUM'),
            'development' => JText::_('COM_CONFIG_FIELD_VALUE_DEVELOPMENT'),
        );

        return $options[$data['error_reporting']];
    }

    public function getActiveTab()
    {
        return JFactory::getApplication()->input->cookie->getCmd('com_lovefactory_settings_tab', 'general');
    }

    protected function save($data)
    {
        $settings = JComponentHelper::getParams('com_lovefactory');

        $registry = new \Joomla\Registry\Registry($data);
        $settings->merge($registry, true);

        $extension = JTable::getInstance('Extension');
        $extension->load(array('type' => 'component', 'element' => 'com_lovefactory'));
        $extension->params = $settings->toString();

        if (!$extension->store()) {
            return false;
        }

        return true;
    }
}
