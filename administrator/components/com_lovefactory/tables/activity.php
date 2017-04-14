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

class TableActivity extends JTable
{
    public $id;
    public $event;
    public $sender_id;
    public $receiver_id;
    public $item_id;
    public $params;
    public $deleted_by_sender;
    public $deleted_by_receiver;
    public $created_at;

    protected $display_name;
    protected $mode;

    public function __construct(&$db)
    {
        parent::__construct('#__lovefactory_activity', 'id', $db);
    }

    public function check()
    {
        if (!parent::check()) {
            return false;
        }

        if (is_null($this->created_at)) {
            $this->created_at = JFactory::getDate()->toSql();
        }

        return true;
    }

    public function load($keys = null, $reset = true)
    {
        if (!parent::load($keys, $reset)) {
            return false;
        }

        $this->params = new JRegistry($this->params);

        return true;
    }

    public function bind($src, $ignore = array())
    {
        if (is_array($src) && isset($src['params']) && is_array($src['params'])) {
            $registry = new JRegistry($src['params']);
            $src['params'] = $registry->toString();
        }

        return parent::bind($src, $ignore);
    }

    public function register($event, $sender_id, $receiver_id, $item_id, $params = array(), $created_at = null)
    {
        // Check if activity stream and event registration are enabled.
        if (!$this->isEventEnabled($event)) {
            return true;
        }

        $params = new \Joomla\Registry\Registry($params);

        $data = array(
            'event' => $event,
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'item_id' => $item_id,
            'params' => $params->toString(),
            'created_at' => $created_at,
        );

        if (!$this->save($data)) {
            return false;
        }

        return true;
    }

    public function getTitle()
    {
        $html = array();
        $user = 'sent' == $this->mode ? $this->receiver_id : $this->sender_id;
        $vars = array('activity_' . $this->event . '_' . $this->mode);

        switch ($this->event) {
            case 'profile_comment':
                $vars[] = FactoryRoute::view('profile&user_id=' . $user);
                $vars[] = $this->display_name;
                break;

            case 'photo_comment':
                $vars[] = FactoryRoute::view('photo&id=' . $this->item_id);

                if ('received' == $this->mode) {
                    $vars[] = FactoryRoute::view('profile&user_id=' . $user);
                    $vars[] = $this->display_name;
                }
                break;

            case 'video_comment':
                $vars[] = FactoryRoute::view('video&id=' . $this->item_id);

                if ('received' == $this->mode) {
                    $vars[] = FactoryRoute::view('profile&user_id=' . $user);
                    $vars[] = $this->display_name;
                }
                break;

            case 'video_add':
                $vars[] = FactoryRoute::view('video&id=' . $this->item_id);
                break;

            case 'photo_add':
                $vars[] = FactoryRoute::view('photo&id=' . $this->item_id);
                break;

            case 'rating':
                $vars[] = $this->params->get('rating');
                $vars[] = FactoryRoute::view('profile&user_id=' . $user);
                $vars[] = $this->display_name;

                if (false === $this->params->get('isNew', true)) {
                    $vars[0] .= '_update';
                }
                break;

            case 'friend_add':
                $vars[] = FactoryRoute::view('profile&user_id=' . $user);
                $vars[] = $this->display_name;
                break;

            case 'relationship_add':
                $vars[] = FactoryRoute::view('profile&user_id=' . $user);
                $vars[] = $this->display_name;
                break;

            case 'group_create':
                $vars[] = FactoryRoute::view('group&id=' . $this->item_id);
                $vars[] = $this->params->get('title');
                break;

            case 'group_join':
                $vars[] = FactoryRoute::view('group&id=' . $this->item_id);
                $vars[] = $this->params->get('title');
                break;
        }

        $html[] = call_user_func_array(array('FactoryText', 'sprintf'), $vars);

        return implode("\n", $html);
    }

    public function getInfo()
    {
        $html = array();

        switch ($this->event) {
            case 'status_update':
                $html[] = $this->params->get('status', '');
                break;

            case 'video_comment':
            case 'photo_comment':
            case 'profile_comment':
                $type = explode('_', $this->event);
                $type = reset($type);

                if (!$this->isRestrictionAllowed('comment_' . $type . '_access', JFactory::getUser()->id)) {
                    return '';
                }

                $html[] = $this->params->get('comment', '');
                break;
        }

        if (!$html) {
            return '';
        }

        return '<blockquote>&quot;' . implode("\n", $html) . '&quot;</blockquote>';
    }

    public function isValid()
    {
        $this->element = $this->getElement();

        return $this->element;
    }

    public function softDelete($userId)
    {
        // Check if it's self action
        if ($this->sender_id == $userId && $this->receiver_id == $userId) {
            $this->deleted_by_sender = 1;
            $this->deleted_by_receiver = 1;
        } elseif ($this->sender_id == $userId) {
            $this->deleted_by_sender = 1;
        } else {
            $this->deleted_by_receiver = 1;
        }

        return $this->store();
    }

    protected function getElement()
    {
        switch ($this->event) {
            case 'comment_received':
                $dbo = $this->getDbo();
                $query = $dbo->getQuery(true)
                    ->select('c.*, u.username')
                    ->from('#__lovefactory_comments c')
                    ->leftJoin('#__users u ON u.id = c.sender_id')
                    ->where('c.id = ' . $dbo->quote($this->params->get('comment_id')));
                $result = $dbo->setQuery($query)
                    ->loadObject();
                break;

            case 'rating_received':
                $dbo = $this->getDbo();
                $query = $dbo->getQuery(true)
                    ->select('r.*, u.username')
                    ->from('#__lovefactory_ratings r')
                    ->leftJoin('#__users u ON u.id = r.sender_id')
                    ->where('r.id = ' . $dbo->quote($this->params->get('rating_id')));
                $result = $dbo->setQuery($query)
                    ->loadObject();
                break;
        }

        return $result;
    }

    protected function isEventEnabled($event)
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        // Check if activity stream page is enabled.
        if (!$settings->enable_wallpage) {
            return false;
        }

        $events = array(
            'status_update' => 'wallpage_add_status',
            'photo_add' => 'wallpage_add_photo',
            'rating' => 'wallpage_add_rating',
            'profile_comment' => 'wallpage_add_comment',
            'photo_comment' => 'wallpage_add_photo_comment',
            'video_comment' => 'wallpage_add_video_comment',
            'video_add' => 'wallpage_add_video',
            'friend_add' => 'wallpage_add_friend',
            'relationship_add' => 'wallpage_add_relationship',
            'group_create' => 'wallpage_create_group',
            'group_join' => 'wallpage_join_group',
        );

        // Check if event is enabled.
        if (!isset($settings->{$events[$event]}) || !$settings->{$events[$event]}) {
            return false;
        }

        return true;
    }

    protected function isRestrictionAllowed($restriction, $userId)
    {
        static $restrictions = array();

        $hash = md5($restriction . '.' . $userId);

        if (!isset($restrictions[$hash])) {
            $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction($restriction);

            try {
                $restriction->isAllowed($userId);
                $restrictions[$hash] = true;
            } catch (Exception $e) {
                $restrictions[$hash] = false;
            }
        }

        return $restrictions[$hash];
    }
}
