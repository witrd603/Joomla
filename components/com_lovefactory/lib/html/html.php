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

class JHtmlLoveFactory
{
    public static function profileThumb($profile, $Itemid = '', $mode = null, $options = array())
    {
        $Itemid = '' == $Itemid ? JFactory::getApplication()->input->getInt('Itemid') : $Itemid;

        $style = 'style="background-image: url(' . $profile->getProfilePhotoSource(true) . ');"';
        $link = JRoute::_('index.php?option=com_lovefactory&view=profile&user_id=' . $profile->user_id . '&Itemid=' . $Itemid);
        $target = isset($options['target']) && 'blank' == $options['target'] ? 'target="_blank"' : '';

        $html = array();

        $html[] = '<div class="lovefactory-profile-thumbnail">';
        $html[] = '  <div class="lovefactory-thumbnail" ' . $style . '>';
        $html[] = '    <a href="' . $link . '" ' . $target . '></a>';
        $html[] = '  </div>';

        if (!isset($options['hideUsername']) || !$options['hideUsername']) {
            $html[] = '  <a href="' . $link . '" ' . $target . '>' . $profile->username . '</a>';
        }

        switch ($mode) {
            case 'latest':
            case 'viewed':
            case 'visitors':
                $html[] = '<div>' . JHtml::_('LoveFactory.date', $profile->date) . '</div>';
                break;

            case 'rating':
                $html[] = '<div><i class="factory-icon icon-star"></i>' . $profile->rating . '</div>';
                break;

            case 'birthday':
                $month = substr($profile->date, 4, 2);
                $day = substr($profile->date, 6, 2);

                $birthday = new DateTime(JFactory::getDate()->year . '-' . $month . '-' . $day);

                $html[] = '<div>' . JHtml::_('LoveFactory.dateRelativeUpcoming', $birthday) . '</div>';
                break;

            case 'visit':
                $html[] = '<div>' . JHtml::_('LoveFactory.date', $profile->lastvisit) . '</div>';
                break;
        }

        $html[] = '</div>';

        return implode("\n", $html);
    }

    public static function membershipDate($date)
    {
        if ('0000-00-00 00:00:00' == $date) {
            return JText::_('MEMBERSHIP_UNLIMITED');
        }

        return JHtml::date($date, 'Y-m-d H:i:s');
    }

    public static function rating($userId)
    {
        $html = array();

        for ($i = 0; $i < 10; $i++) {
            $html[] = '<a href="' . FactoryRoute::task('rating.add&format=raw&user_id=' . $userId . '&rating=' . ($i + 1)) . '" data-user-id="' . $userId . '" class="lovefactory-rating-star"><i class="factory-icon icon-star-empty"></i></a>';
        }

        return implode('', $html);
    }

    public static function toolbar($toolbar, $selected, $user_id = null)
    {
        if (is_null($user_id)) {
            $user_id = JFactory::getApplication()->input->getInt('user_id', JFactory::getUser()->id);
        }

        $user = JFactory::getUser($user_id ? $user_id : null);

        if (!$user->id && !in_array($toolbar, array('search'))) {
            throw new Exception(FactoryText::_('user_not_found'), 404);
        }

        $profile = JTable::getInstance('Profile', 'Table');
        $profile->load($user->id);

        $route = '';
        if ($user_id != JFactory::getUser()->id) {
            $route = '&user_id=' . $user_id;
        }

        $toolbars = array(
            'profile' => array(
                'profile' => (object)array(
                    'link' => FactoryRoute::view('profile' . $route),
                    'text' => FactoryText::sprintf('toolbar_profile_profile', $profile->display_name),
                    'icon' => 'icon-user'),
                'comments' => (object)array(
                    'link' => FactoryRoute::view('comments' . $route),
                    'text' => FactoryText::_('toolbar_profile_comments'),
                    'icon' => 'icon-balloon'),
                'activity' => (object)array(
                    'link' => FactoryRoute::view('activity' . $route),
                    'text' => FactoryText::_('toolbar_profile_activity'),
                    'icon' => 'icon-information'),
                'friends' => (object)array(
                    'link' => FactoryRoute::view('friends' . $route),
                    'text' => FactoryText::_('toolbar_profile_friends'),
                    'icon' => 'icon-users'),
            ),

            'friends' => array(
                'topfriends' => (object)array(
                    'link' => FactoryRoute::view('topfriends'),
                    'text' => FactoryText::_('toolbar_friends_top_friends'),
                    'icon' => 'icon-star'),
                'friends' => (object)array(
                    'link' => FactoryRoute::view('myfriends'),
                    'text' => FactoryText::_('toolbar_friends_my_friends'),
                    'icon' => 'icon-users'),
                'relationship' => (object)array(
                    'link' => FactoryRoute::view('myrelationship'),
                    'text' => FactoryText::_('toolbar_friends_relationship'),
                    'icon' => 'icon-heart'),
                'requests' => (object)array(
                    'link' => FactoryRoute::view('requests'),
                    'text' => FactoryText::_('toolbar_friends_requests'),
                    'icon' => 'icon-user-plus'),
                'blocked' => (object)array(
                    'link' => FactoryRoute::view('blocked'),
                    'text' => FactoryText::_('toolbar_friends_blocked'),
                    'icon' => 'icon-cross-circle'),
            ),

            'messages' => array(
                'inbox' => (object)array(
                    'link' => FactoryRoute::view('inbox'),
                    'text' => FactoryText::_('toolbar_messages_inbox'),
                    'icon' => 'icon-mail'),
                'outbox' => (object)array(
                    'link' => FactoryRoute::view('outbox'),
                    'text' => FactoryText::_('toolbar_messages_outbox'),
                    'icon' => 'icon-mail-reply'),
                'compose' => (object)array(
                    'link' => FactoryRoute::view('compose'),
                    'text' => FactoryText::_('toolbar_messages_compose'),
                    'icon' => 'icon-mail-pencil'),
                'interactions' => (object)array(
                    'link' => FactoryRoute::view('interactions'),
                    'text' => FactoryText::_('toolbar_messages_interactions'),
                    'icon' => 'icon-smiley'),
            ),

            'search' => array(
                'search' => (object)array(
                    'link' => FactoryRoute::view('search'),
                    'text' => FactoryText::_('toolbar_search_search'),
                    'icon' => 'icon-magnifier'),
                'advanced' => (object)array(
                    'link' => FactoryRoute::view('advanced'),
                    'text' => FactoryText::_('toolbar_search_advanced'),
                    'icon' => 'icon-magnifier--arrow'),
                'radius' => (object)array(
                    'link' => FactoryRoute::view('radiussearch'),
                    'text' => FactoryText::_('toolbar_search_radius'),
                    'icon' => 'icon-map'),
            ),

            'gallery' => array(
                'profile' => (object)array(
                    'link' => FactoryRoute::view('profile' . $route),
                    'text' => FactoryText::sprintf('toolbar_profile_profile', $profile->display_name),
                    'icon' => 'icon-user'),
                'photos' => (object)array(
                    'link' => FactoryRoute::view('photos' . $route),
                    'text' => FactoryText::_('toolbar_gallery_photos'),
                    'icon' => 'icon-image'),
                'videos' => (object)array(
                    'link' => FactoryRoute::view('videos' . $route),
                    'text' => FactoryText::_('toolbar_gallery_videos'),
                    'icon' => 'icon-film'),
            ),
        );

        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if (!$settings->enable_relationships) {
            unset($toolbars['friends']['relationship']);
        }

        if (!$settings->enable_friends) {
            unset($toolbars['profile']['friends']);
            unset($toolbars['friends']['friends']);
            unset($toolbars['friends']['topfriends']);
        }

        if (!$settings->enable_top_friends) {
            unset($toolbars['friends']['topfriends']);
        }

        if (!$settings->enable_comments) {
            unset($toolbars['profile']['comments']);
        }

        if (!$settings->enable_wallpage) {
            unset($toolbars['profile']['activity']);
        }

        if (!$settings->enable_search_radius) {
            unset($toolbars['search']['radius']);
        }

        if (!$settings->enable_interactions) {
            unset($toolbars['messages']['interactions']);
        }

        // Privacy settings.
        if ($user_id != JFactory::getUser()->id) {
            if ('profile' == $toolbar) {
                $modelFriend = JModelLegacy::getInstance('Friend', 'FrontendModel');
                $isFriend = 1 == $modelFriend->getFriendshipStatus($user_id, JFactory::getUser()->id);

                $table = JTable::getInstance('Profile', 'Table');
                $table->load($user_id);

                $settings = JComponentHelper::getParams('com_lovefactory');
                $table->setSettings($settings);

                $privacyComments = $table->getParameter('privacy.comments');
                if ('private' == $privacyComments || ('friends' == $privacyComments && !$isFriend)) {
                    unset($toolbars['profile']['comments']);
                }

                $privacyActivityStream = $table->getParameter('privacy.activity_stream');
                if ('private' == $privacyActivityStream || ('friends' == $privacyActivityStream && !$isFriend)) {
                    unset($toolbars['profile']['activity']);
                }

                $privacyFriends = $table->getParameter('privacy.friends');
                if ('private' == $privacyFriends || ('friends' == $privacyFriends && !$isFriend)) {
                    unset($toolbars['profile']['friends']);
                }
            }
        }

        $counters = array();
        $models = array(
            'profile' => 'Profile',
            'friends' => 'MyFriends',
            'messages' => 'Inbox',
            'search' => 'Search'
        );

        if ($user_id == JFactory::getUser()->id && isset($models[$toolbar])) {
            $model = JModelLegacy::getInstance($models[$toolbar], 'FrontendModel');
            if ($model && method_exists($model, 'getCounters')) {
                $counters = $model->getCounters();
            }
        }

        return self::renderToolbar($toolbars[$toolbar], $selected, $counters);
    }

    protected static function renderToolbar($items, $selected, $counters)
    {
        $html = array();
        $first = true;

        $html[] = '<div id="lovefactory-toolbar">';
        $html[] = '<ul>';

        foreach ($items as $key => $item) {
            $counter = '';
            if (isset($counters[$key])) {
                $counter = '<i class="factory-icon icon-counter-' . ($counters[$key] > 20 ? 'more' : $counters[$key]) . ' counter"></i>';
            }

            $html[] = '<li class="' . ($key == $selected ? 'active' : '') . ' ' . ($first ? 'first' : '') . '">';
            $html[] = '<a href="' . $item->link . '">';

            $html[] = '<span class="hidden-phone">';
            $html[] = $item->text . $counter;
            $html[] = '</span>';

            $html[] = '<span class="visible-phone">';
            $html[] = '<i class="factory-icon ' . $item->icon . '"></i>' . $counter;
            $html[] = '</span>';

            $html[] = '</a>';

            $html[] = '</li>';
            $first = false;
        }

        $html[] = '</ul>';
        $html[] = '</div>';

        return implode("\n", $html);
    }

    public static function nl2p($string)
    {
        $html = '<p>' . preg_replace('#(<br\s*?/?>\s*?){2,}#', '</p><p>', nl2br($string, true)) . '</p>';

        return $html;
    }

    public static function format_date($date, $mode = 'date', $format = 'Y-m-d H:i:s', $prefix = '')
    {
        if ('' != $prefix) {
            $prefix .= '_';
        }

        if (is_null($date) || !$date || JFactory::getDbo()->getNullDate() == $date) {
            return FactoryText::_($prefix . 'time_ago_never');
        }

        if (!is_numeric($date)) {
            $date = JFactory::getDate($date)->toUnix();
        }

        switch ($mode) {
            case 'ago':
            case 'birthdate':
                $joomla_date = JFactory::getDate();
                $difference = $joomla_date->toUnix() - $date;

                if ('birthdate' == $mode) {
                    $difference = abs($difference);
                } elseif ($difference < 0) {
                    $difference = 0;
                }

                if ($difference < 3600) {
                    $difference = floor($difference / 60);
                    if (0 == $difference) {
                        $output = FactoryText::_($prefix . 'time_' . $mode . '_seconds');
                    } else {
                        $output = FactoryText::plural($prefix . 'time_' . $mode . '_minute', $difference);
                    }
                } elseif ($difference < 3600 * 24) {
                    $difference = floor($difference / 3600);
                    $output = FactoryText::plural($prefix . 'time_' . $mode . '_hour', $difference);
                } else {
                    $difference = floor($difference / 3600 / 24);
                    $output = FactoryText::plural($prefix . 'time_' . $mode . '_day', $difference);
                }
                break;

            case 'date':
                $output = JHtml::date($date, $format);
                break;
        }

        return $output;
    }

    public static function date($date, $format = null)
    {
        static $users = array();

        if (is_null($format)) {
            // Calculate user format.
            $user = JFactory::getUser();
            $format = 0;

            if (!$user->guest) {
                if (!isset($users[$user->id])) {
                    $table = JTable::getInstance('Profile', 'Table');
                    $table->load($user->id);

                    $settings = JComponentHelper::getParams('com_lovefactory');
                    $table->setSettings($settings);

                    $users[$user->id] = $table;
                }

                $format = $users[$user->id]->getParameter('date_format');
            }

            // Calculate site format.
            if (0 === (int)$format) {
                $settings = LoveFactoryApplication::getInstance()->getSettings();
                $format = 'custom' == $settings->date_format ? $settings->date_custom_format : $settings->date_format;
            }
        }

        if ('ago' == $format) {
            return self::format_date($date, 'ago');
        }

        return self::format_date($date, 'date', $format);
    }

    public static function infobar($settings, $location, $data)
    {
        // Initialise variables.
        $params = JComponentHelper::getParams('com_lovefactory');
        $document = JFactory::getDocument();
        $html = array();

        JHtml::stylesheet('components/com_lovefactory/assets/css/main.css');
        JHtml::stylesheet('components/com_lovefactory/assets/css/icons.css');
        JHtml::stylesheet('components/com_lovefactory/assets/css/infobar.css');
        JHtml::stylesheet('components/com_lovefactory/assets/css/jquery.tipsy.css');

        JHtml::_('jquery.framework');

        //JHtml::script('components/com_lovefactory/assets/js/jquery.js');
        JHtml::script('components/com_lovefactory/assets/js/jquery.tipsy.js');
        JHtml::script('components/com_lovefactory/assets/js/infobar.js');
        //JHtml::script('components/com_lovefactory/assets/js/jquery.noconflict.js');
        JHtml::script('components/com_lovefactory/assets/js/main.js');

        JHtml::_('behavior.framework');

        $document->addScriptDeclaration('LoveFactory.set(' . json_encode(array('routeInfobarUpdate' => JRoute::_('index.php?option=com_lovefactory&controller=infobar&task=update', false, JUri::getInstance()->isSSL() ? 1 : 2))) . ');');

        $showLabels = $params->get('infobar.buttons_labels', 0);
        $refreshInterval = $params->get('infobar.refresh_interval', 30);

        $isTipsy = $showLabels ? '' : 'is-tipsy';
        $class = !$showLabels ? 'no-labels' : '';

        $html[] = '<div class="lovefactory-infobar-wrapper lovefactory-view ' . (2 == $location ? 'lovefactory-infobar-top' : '') . ' ' . $class . '" rel="' . $refreshInterval . '">';

        $buttons = array(
            'friends' => array(
                'view' => 'myfriends',
                'depends' => $settings->enable_friends,
                'title' => JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_FRIENDS_TOOLTIP'),
                'text' => '<i class="factory-icon icon-users"></i><span>' . JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_MY_FRIENDS') . '</span>',
            ),

            'requests' => array(
                'depends' => $settings->enable_friends,
                'class' => 'lovefactory-infobar-requests',
                'title' => JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_REQUESTS_TOOLTIP'),
                'text' => '<i class="factory-icon icon-user-plus"></i><span>' . $data['requests'] . '</span>' . ($showLabels ? JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_REQUESTS') : ''),
            ),

            'spacer_1' => 'spacer',

            'messages' => array(
                'depends' => $settings->enable_messages,
                'view' => 'inbox',
                'class' => 'lovefactory-infobar-messages',
                'title' => JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_MESSAGES_TOOLTIP'),
                'text' => '<i class="factory-icon icon-mail"></i><span>' . $data['messages'] . '</span>' . ($showLabels ? JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_MESSAGES') : ''),
            ),

            'comments' => array(
                'depends' => isset($data['comments']),
                'class' => 'lovefactory-infobar-comments',
                'title' => JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_COMMENTS_TOOLTIP'),
                'text' => '<i class="factory-icon icon-balloon"></i><span>' . $data['comments'] . '</span>' . ($showLabels ? JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_COMMENTS') : ''),
            ),

            'interactions' => array(
                'depends' => $settings->enable_interactions,
                'class' => 'lovefactory-infobar-interactions',
                'title' => JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_INTERACTIONS_TOOLTIP'),
                'text' => '<i class="factory-icon icon-smiley"></i><span>' . $data['interactions'] . '</span>' . ($showLabels ? JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_INTERACTIONS') : ''),
            ),

            'spacer_2' => 'spacer',

            'profile_view' => array(
                'view' => 'profile',
                'title' => JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_VIEW_PROFILE_TOOLTIP'),
                'text' => '<i class="factory-icon icon-user"></i><span>' . JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_VIEW_PROFILE') . '</span>',
            ),

            'profile_edit' => array(
                'view' => 'edit',
                'title' => JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_UPDATE_PROFILE_TOOLTIP'),
                'text' => '<i class="factory-icon icon-user-pencil"></i><span>' . JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_UPDATE_PROFILE') . '</span>',
            ),

            'gallery' => array(
                'view' => 'photos',
                'title' => JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_GALLERY_TOOLTIP'),
                'text' => '<i class="factory-icon icon-photo-album"></i><span>' . JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_MY_GALLERY') . '</span>',
            ),

            'spacer_3' => 'spacer',
        );

        foreach ($buttons as $button => $options) {
            if ('spacer' === $options) {
                $html[] = '<div class="lovefactory-infobar-spacer"></div>';
                continue;
            }

            // Check if button is enabled from settings.
            if (!$params->get('infobar.button_' . $button . '_enabled', 1)) {
                continue;
            }

            // Check if button dependencies are enabled.
            if (isset($button['depends']) && !$options['depends']) {
                continue;
            }

            $view = isset($options['view']) ? $options['view'] : $button;
            $itemId = $params->get('infobar.button_' . $button . '_itemid', 0);

            if (!$itemId) {
                $url = JRoute::_('index.php?option=com_lovefactory&view=' . $view);
            } else {
                if ('append' === $params->get('infobar.button_' . $button . '_itemid_usage', 'append')) {
                    $url = JRoute::_('index.php?option=com_lovefactory&view=' . $view . '&Itemid=' . $itemId);
                } else {
                    $url = JRoute::_('index.php?Itemid=' . $itemId);
                }
            }

            $class = (isset($options['class']) ? $options['class'] : '') . ' ' . $isTipsy;

            $html[] = '<a href="' . $url . '" class="' . $class . '" title="' . $options['title'] . '">' . $options['text'] . '</a>';
        }

        if ($params->get('infobar.button_logout_enabled', 1)) {
            $html[] = '<a href="' . JRoute::_('index.php?option=com_users&task=user.logout&' . JSession::getFormToken() . '=1') . '" class="' . $isTipsy . '" title="' . JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_LOGOUT_TOOLTIP') . '"><i class="factory-icon icon-door-open-out"></i><span>' . JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_LOGOUT') . '</span></a>';
        }

        if ($params->get('infobar.button_close_enabled', 1)) {
            $html[] = '<a href="' . JRoute::_('index.php?option=com_lovefactory&controller=infobar&task=close') . '" id="lovefactory-infobar-close" class="' . $isTipsy . '" title="' . JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_CLOSE') . '"><i class="factory-icon icon-cross-circle"></i><span>' . JText::_('COM_LOVEFACTORY_INFOBAR_BUTTON_CLOSE') . '</span></a>';
        }

        $html[] = '</div>';

        $document->setBuffer($document->getBuffer('component') . implode("\n", $html), 'component');
    }

    public static function QuickMessage($userId)
    {
        static $framework_loaded = false;

        if ($userId === JFactory::getUser()->id) {
            return false;
        }

        // Check if messages are enabled.
        if (!LoveFactoryApplication::getInstance()->getSettings('enable_messages')) {
            return false;
        }

        // Check if framework has been loaded.
        if (!$framework_loaded) {
            $document = JFactory::getDocument();
            $document->addScriptDeclaration('jQuery(document).ready(function ($) { $(".lovefactory-quick-message").loveFactoryQuickMessage(); });');

            FactoryHtml::script('lovefactory');

            $framework_loaded = true;
        }

        $html = array();

        if (JFactory::getUser()->guest) {
            $html[] = self::loginLink(FactoryText::_('login_quick_message'));
        } else {
            $html[] = '<a href="' . FactoryRoute::view('dialog&layout=quickmessage&format=raw&user_id=' . $userId) . '" class="lovefactory-quick-message" id="lovefactory-quickmessage-user-' . $userId . '"><i class="factory-icon icon-mail-arrow"></i>' . FactoryText::_('quick_message_button_title') . '</a>';
        }

        return implode("\n", $html);
    }

    public static function TopFriendButton($userId, $isTopFriend = false)
    {
        // Check if top friends are enabled.
        if (!LoveFactoryApplication::getInstance()->getSettings('enable_top_friends')) {
            return false;
        }

        $class = $isTopFriend ? 'icon-star-minus' : 'icon-star-plus';
        $text = $isTopFriend ? 'topfriend_demote_top_friend' : 'topfriend_promote_top_friend';
        $task = $isTopFriend ? 'demote' : 'promote';

        $html = array();

        $html[] = '<a href="' . FactoryRoute::task('friend.' . $task . '&user_id=' . $userId) . '" class="lovefactory-top-friend lovefactory-ajax-action" id="lovefactory-topfriend-user-' . $userId . '"><i class="factory-icon ' . $class . '"></i><span>' . FactoryText::_($text) . '</span></a>';

        return implode("\n", $html);
    }

    public static function FriendshipButton($userId, $status = null)
    {
        static $framework_loaded = false;

        if ($userId === JFactory::getUser()->id) {
            return false;
        }

        // Check if friends are enabled.
        if (!LoveFactoryApplication::getInstance()->getSettings('enable_friends')) {
            return false;
        }

        // Initialise variables.
        $html = array();

        $texts = array(
            0 => FactoryText::_('button_friendship_text_ask_friendship'),
            1 => FactoryText::_('button_friendship_text_break_friendship'),
            2 => FactoryText::_('button_friendship_text_cancel_pending_request'),
            3 => FactoryText::_('button_friendship_text_view_pending_request'),
        );
        $icons = array(
            0 => 'user-plus',
            1 => 'user-minus',
            2 => 'cross-circle',
            3 => 'hourglass-arrow'
        );
        $urls = array(
            0 => FactoryRoute::view('dialog&layout=friendship&format=raw&user_id=' . $userId),
            1 => FactoryRoute::task('friend.remove&id=' . $userId),
            2 => FactoryRoute::task('friend.cancel&id=' . $userId),
            3 => FactoryRoute::view('requests'),
        );
        $class = array(
            0 => '',
            1 => 'lovefactory-ajax-action',
            2 => 'lovefactory-ajax-action',
            3 => ''
        );
        $confirms = array(
            1 => FactoryText::_('button_friendship_break_friendsship_confirm'),
        );

        // Check if framework has been loaded.
        if (!$framework_loaded) {
            $document = JFactory::getDocument();
            $document->addScriptDeclaration('jQuery(document).ready(function ($) { $.LoveFactoryButtonFriendship(); });');

            FactoryHtml::script('lovefactory');
            FactoryText::script('remove_friend_confirm');

            $framework_loaded = true;
        }

        // Get current friendship status.
        if (is_null($status)) {
            $model = JModelLegacy::getInstance('Friend', 'FrontendModel');
            $status = $model->getFriendshipStatus(JFactory::getUser()->id, $userId);
        }

        $confirm = '';
        if (isset($confirms[$status])) {
            $confirm = 'data-confirm="' . $confirms[$status] . '"';
        }

        if (JFactory::getUser()->guest) {
            $html[] = self::loginLink(FactoryText::_('login_ask_friendship'));
        } else {
            $html[] = '<a href="' . $urls[$status] . '" ' . $confirm . ' class="button-friendship ' . $class[$status] . '" data-status="' . $status . '"><i class="factory-icon icon-' . $icons[$status] . '"></i><span>' . $texts[$status] . '</span></a>';
        }

        return implode("\n", $html);
    }

    public static function RelationshipButton($userId, $status = null)
    {
        static $framework_loaded = false;

        // Check if relationship are enabled.
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        if (!$settings->enable_relationships) {
            return false;
        }

        // Initialise variables.
        $html = array();

        $texts = array(
            0 => FactoryText::_('button_relationship_text_ask_relationship'),
            1 => FactoryText::_('button_relationship_text_break_relationship'),
            2 => FactoryText::_('button_relationship_text_cancel_pending_request'),
            3 => FactoryText::_('button_relationship_text_view_pending_request'),
        );
        $icons = array(
            0 => 'heart-plus',
            1 => 'heart-minus',
            2 => 'cross-circle',
            3 => 'hourglass-arrow'
        );
        $urls = array(
            0 => FactoryRoute::view('dialog&layout=relationship&format=raw&user_id=' . $userId),
            1 => FactoryRoute::task('relationship.remove&id=' . $userId),
            2 => FactoryRoute::task('relationship.cancel&id=' . $userId),
            3 => FactoryRoute::view('requests'),
        );
        $class = array(
            0 => '',
            1 => 'lovefactory-ajax-action',
            2 => 'lovefactory-ajax-action',
            3 => ''
        );

        // Check if framework has been loaded.
        if (!$framework_loaded) {
            $document = JFactory::getDocument();
            $document->addScriptDeclaration('jQuery(document).ready(function ($) { $.LoveFactoryButtonRelationship(); });');

            FactoryHtml::script('lovefactory');

            $framework_loaded = true;
        }

        // Get current friendship status.
        if (is_null($status)) {
            $model = JModelLegacy::getInstance('Friend', 'FrontendModel');
            $status = $model->getFriendshipStatus(JFactory::getUser()->id, $userId, 2);
        }

        $html[] = '<a href="' . $urls[$status] . '" class="button-relationship ' . $class[$status] . '" data-status="' . $status . '"><i class="factory-icon icon-' . $icons[$status] . '"></i><span>' . $texts[$status] . '</span></a>';

        return implode("\n", $html);
    }

    public static function BlockButton($userId, $isBlocked)
    {
        $class = $isBlocked ? 'icon-user-minus' : 'icon-user-plus';
        $text = $isBlocked ? 'profile_interact_unblock_user' : 'profile_interact_block_user';
        $task = $isBlocked ? 'remove' : 'add';

        $html = array();

        $html[] = '<a href="' . FactoryRoute::task('blacklist.' . $task . '&user_id=' . $userId) . '" class="lovefactory-ajax-action"><i class="factory-icon ' . $class . '"></i><span>' . FactoryText::_($text) . '</span></a>';

        return implode("\n", $html);
    }

    public static function InteractionButton($userId, $interaction)
    {
        // Initialise variables.
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        // Check if interactions are enabled.
        if (!$settings->enable_interactions) {
            return false;
        }

        // Check if interaction is enabled.
        if (!isset($settings->{'enable_interaction_' . $interaction}) || !$settings->{'enable_interaction_' . $interaction}) {
            return false;
        }

        $types = array(
            'wink' => 5,
            'kiss' => 1,
            'hug' => 3,
        );

        // Check for user uploaded smiley icon.
        jimport('joomla.filesystem.folder');
        $file = JFolder::files(LoveFactoryApplication::getInstance()->getStorageFolder() . DS . 'interactions', $interaction);
        if (isset($file[0])) {
            $attribs = 'class="factory-icon" style="background-image: url(\'' . LoveFactoryApplication::getInstance()->getStorageFolder(true) . '/interactions/' . $file[0] . '\');"';
        } else {
            $attribs = 'class="factory-icon icon-smiley"';
        }

        $html = array();

        $html[] = '<a href="' . FactoryRoute::task('interaction.send&user_id=' . $userId . '&type_id=' . $types[$interaction]) . '" class="lovefactory-ajax-action">';
        $html[] = '<i ' . $attribs . '></i><span>' . FactoryText::_('profile_interact_send_interaction_' . $interaction) . '</span>';
        $html[] = '</a>';

        return implode("\n", $html);
    }

    public static function beginForm($url, $method = 'GET', $name = 'adminForm', $class = 'lovefactory-form')
    {
        $html = array();
        $app = JFactory::getApplication();

        // Add the start form tag.
        $html[] = '<form action="' . $url . '" method="' . $method . '" id="' . $name . '" name="' . $name . '" class="' . $class . '">';

        // Check if SEF it's not enabled.
        if (!$app->get('sef', 0)) {
            $url = parse_url($url);
            parse_str($url['query'], $output);

            foreach ($output as $key => $value) {
                $html[] = '<input type="hidden" name="' . $key . '" value="' . $value . '">';
            }
        }

        return implode("\n", $html);
    }

    public static function privacyButton($privacy = 'public', $config = array())
    {
        static $loaded = false;

        if (!$loaded) {
            $document = JFactory::getDocument();
            $document->addScriptDeclaration('jQuery(document).ready(function ($) { $(".privacy-button").privacyButton(); }); ');
            $loaded = true;
        }

        JHtml::_('FactoryFramework.behavior', 'privacyButton');

        $icons = array(
            'public' => 'globe',
            'friends' => 'users',
            'private' => 'lock',
        );

        $settings = LoveFactoryApplication::getInstance()->getSettings();

        $html = array();

        if (isset($config['readonly']) && $config['readonly']) {
            $html[] = '<div class="privacy-button privacy-toggle">';
            $html[] = '<i class="factory-icon icon-' . $icons[$privacy] . ' privacy-current"></i>';
            $html[] = '</div>';
        } else {
            $html[] = '<div class="privacy-button">';
            $html[] = '<a href="#" class="privacy-toggle"><i class="factory-icon icon-' . $icons[$privacy] . ' privacy-current"></i><i class="factory-icon icon-control-270-small"></i></a>';

            $html[] = '<ul class="privacy-options" style="display: none;">';
            $html[] = '<li><a href="#" class="privacy-public"><i class="factory-icon icon-globe"></i>' . FactoryText::_('privacy_button_privacy_everyone') . '</a></li>';

            if ($settings->enable_friends) {
                $html[] = '<li><a href="#" class="privacy-friends"><i class="factory-icon icon-users"></i>' . FactoryText::_('privacy_button_privacy_friends') . '</a></li>';
            }

            $html[] = '<li><a href="#" class="privacy-private"><i class="factory-icon icon-lock"></i>' . FactoryText::_('privacy_button_privacy_only_me') . '</a></li>';
            $html[] = '</ul>';

            if (isset($config['hiddenInput']) && $config['hiddenInput']) {
                $name = isset($config['hiddenInputName']) ? $config['hiddenInputName'] : '';
                $html[] = '<input type="hidden" value="' . $privacy . '" name="' . $name . '" />';
            }
        }

        $html[] = '</div>';

        return implode("\n", $html);
    }

    public static function checkAll()
    {

        JHtml::_('FactoryFramework.behavior', 'checkAll');

        $html = array();

        $html[] = '<input type="checkbox" class="lovefactory-check-all" id="lovefactory-check-all" />';
        $html[] = '<label for="lovefactory-check-all">' . FactoryText::_('batch_actions_select_all') . '</label>';

        return implode("\n", $html);
    }

    public static function reportButton($type, $id, $reported, $options = array())
    {
        static $loaded = false;

        if (isset($options['style']) && 'new' === $options['style']) {
            $icon = '<span class="fa fa-fw fa-warning"></span>';
        } else {
            $icon = '<i class="factory-icon icon-exclamation"></i>';
        }

        if (isset($options['showIcon']) && false == $options['showIcon']) {
            $icon = '';
        }

        if ($reported) {
            return $icon . FactoryText::_('report_item_reported');
        }

        if (!$loaded) {
            $loaded = true;

            $document = JFactory::getDocument();
            $document->addScriptDeclaration('jQuery(document).ready(function ($) { $.LoveFactoryButtonReport(); });');

            JHtmlFactoryFramework::behavior('jQueryUi');
            JHtmlFactoryFramework::behavior('tooltip');
            FactoryHtml::script('lovefactory');
        }

        $html = array();

        $url = FactoryRoute::view('dialog&layout=report&format=raw&params[type]=' . $type . '&params[id]=' . $id);

        $html[] = '<a href="' . $url . '" class="lovefactory-button-report">';
        $html[] = $icon;
        $html[] = '<span>' . FactoryText::_('report_item_report') . '</span>';
        $html[] = '</a>';

        return implode('', $html);
    }

    public static function currency($amount, $currency = null)
    {
        $amount = preg_replace('/[^0-9.]/', '', $amount);
        $amount = number_format($amount, 2, '.', '');

        if (null === $currency) {
            return $amount;
        }

        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if (0 === $settings->currency_symbol) {
            return $currency . ' ' . $amount;
        }

        return $amount . ' ' . $currency;
    }

    public static function loginLink($message)
    {
        $return = base64_encode(JUri::getInstance()->toString());
        $route = JRoute::_('index.php?option=com_users&view=login&return=' . $return);

        return '<a href="' . $route . '"><i class="factory-icon icon-lock"></i>' . $message . '</a>';
    }

    public static function dateRelativeUpcoming(DateTime $date, DateTime $now = null)
    {
        // Get now date.
        if (null === $now) {
            $now = new DateTime(JHtml::_('date', 'now', 'Y-m-d H:i:s'));
        }

        // Calculate difference.
        $difference = $date->getTimestamp() - $now->getTimestamp();

        // Check if date is upcoming.
        if (0 > $difference) {
            return null;
        }

        if (60 > $difference) {
            $output = FactoryText::_('date_relative_upcoming_seconds');
        } elseif (60 * 60 > $difference) {
            $output = FactoryText::plural('date_relative_upcoming_minutes', $difference);
        } elseif (60 * 60 * 24 > $difference) {
            $hours = floor($difference / 3600);
            $output = FactoryText::plural('date_relative_upcoming_hours', $hours);
        } else {
            $days = floor($difference / 3600 / 24);
            $output = FactoryText::plural('date_relative_upcoming_days', $days);
        }

        return $output;
    }
}
