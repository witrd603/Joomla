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

class FrontendModelProfile extends JModelLegacy
{
    protected $_errors = array();
    protected $profile = null;

    public function getProfile($user_id = null)
    {
        static $profiles = array();

        $user = JFactory::getUser();

        if (is_null($user_id)) {
            $user_id = JFactory::getApplication()->input->getInt('user_id', JFactory::getUser()->id);
        }

        if (!isset($profiles[$user_id])) {
            $dbo = $this->getDbo();
            $query = $dbo->getQuery(true)
                ->select('p.*')
                ->from('#__lovefactory_profiles p')
                ->where('p.user_id = ' . $dbo->quote($user_id));

            // Select the username.
            $query->select('u.username')
                ->leftJoin('#__users u ON u.id = p.user_id');

            // Select if users are friends.
            $query->select('f.id AS is_friend')
                ->leftJoin('#__lovefactory_friends f ON ((f.sender_id = p.user_id AND f.receiver_id = ' . $dbo->quote($user->id) . ') OR (f.receiver_id = p.user_id AND f.sender_id = ' . $dbo->quote($user->id) . ')) AND f.pending = ' . $dbo->quote(0));

            // Select if user is blocked.
            $query->select('b.id AS blocked')
                ->leftJoin('#__lovefactory_blacklist b ON b.sender_id = ' . $dbo->quote($user->id) . ' AND b.receiver_id = p.user_id');

            foreach ($this->getPage()->getFields(false) as $field) {
                $field->addQueryView($query);
            }

            // Check if it's my profile.
            if ($user_id != $user->id) {
                $settings = LoveFactoryApplication::getInstance()->getSettings();
                $helper = new \ThePhpFactory\LoveFactory\Helper\OppositeGender($settings);

                if ($helper->isOppositeGenderDisplayEnabled($user)) {
                    $helper->addOppositeGenderSearchCondition($query, $user);
                }
            }

            $result = $dbo->setQuery($query)
                ->loadObject();

            $table = JTable::getInstance('Profile', 'Table');

            if ($result) {
                $table->bind($result);

                foreach ((array)$result as $key => $value) {
                    if (!isset($table->$key)) {
                        $table->$key = $value;
                    }
                }
            }

            $profiles[$user_id] = $table;

            $this->registerProfileVisit($user_id, $user->id);
        }

        // If profile was not found, but it's the current user's profile.
        if ((!isset($profiles[$user_id]) || !$profiles[$user_id]->user_id) && (int)$user->id && (int)$user_id === (int)$user->id) {
            $this->createProfileFromJoomlaUser(array(
                'id' => $user->id,
                'username' => $user->username,
            ));

            JFactory::getApplication()->redirect(JUri::getInstance()->toString());
        }

        // Check if user was found and it's not banned.
        if (!isset($profiles[$user_id]) || !$profiles[$user_id]->user_id || $profiles[$user_id]->banned) {
            throw new Exception(FactoryText::_('profile_not_found_or_banned'), 404);
        }

        // Check if user is allowed to view profile.
        if ($user->id != $user_id && ((2 == $profiles[$user_id]->online) || (1 == $profiles[$user_id]->online && !$profiles[$user_id]->is_friend))) {
            throw new Exception(FactoryText::_('profile_not_available_private_or_friends'), 403);
        }

        $this->profile = $profiles[$user_id];

        return $profiles[$user_id];
    }

    public function getPage($page = 'profile_view', $mode = 'view')
    {
        $page = LoveFactoryPage::getInstance($page, $mode);

        if (null !== $this->profile) {
            $page->bind($this->profile);
        }

        return $page;
    }

    public function login($username)
    {
        $id = $this->getUserIdForUsername($username);

        $this->setLoggedIn($id, 1);
    }

    public function logout($username)
    {
        $id = $this->getUserIdForUsername($username);

        $this->setLoggedIn($id, 0);
    }

    public function getVisitor()
    {
        $user = JFactory::getUser();
        $visitor = (object)array();

        if ($user->guest) {
            $uri = JUri::getInstance();

            $visitor->status = 0;
            $visitor->referer = base64_encode($uri->toString());
        } else {
            $profile = $this->getTable('profile', 'Table');
            $profile->load($user->id);

            $visitor->status = !$profile->validated ? 1 : 2;
        }

        return $visitor;
    }

    public function getRatings()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if (!$settings->enable_rating) {
            return null;
        }

        $profile = $this->getProfile();
        $model = JModelLegacy::getInstance('Ratings', 'FrontendModel');

        $ratings = new stdClass();

        if ($profile) {
            $ratings->myRating = $model->getMyRatingForUser($profile->user_id);
            $ratings->latestRatings = $model->getLatestRatingsForUser($profile->user_id);
            $ratings->allowUpdate = $settings->enable_rating_update;
        }

        return $ratings;
    }

    public function getFriendship()
    {
        $profile = $this->getProfile();
        $user = JFactory::getUser();

        if (!$profile) {
            return false;
        }

        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('f.*')
            ->from('#__lovefactory_friends f')
            ->where('(f.sender_id = ' . $dbo->quote($user->id) . ' AND f.receiver_id = ' . $dbo->quote($profile->user_id) . ')', 'OR')
            ->where('(f.sender_id = ' . $dbo->quote($profile->user_id) . ' AND f.receiver_id = ' . $dbo->quote($user->id) . ')');
        $result = $dbo->setQuery($query)
            ->loadObjectList('type');

        // Friendship
        if (!isset($result[1])) {
            $friendshipText = FactoryText::_('profile_friendship_ask_friendship');
            $friendshipIcon = 'icon-hand-shake';
        } else {
            if ($result[1]->pending) {
                if ($result[1]->sender_id == $user->id) {
                    $friendshipText = FactoryText::_('profile_friendship_cancel_request');
                    $friendshipIcon = 'icon-hourglass-minus';
                } else {
                    $friendshipText = FactoryText::_('profile_friendship_pending_request');
                    $friendshipIcon = 'icon-hourglass-arrow';
                }
            } else {
                $friendshipText = FactoryText::_('profile_friendship_break_friendship');
                $friendshipIcon = 'icon-cross-circle';
            }
        }

        return (object)array(
            'friendshipText' => $friendshipText,
            'friendshipIcon' => $friendshipIcon,
        );
    }

    public function getCounters()
    {
        $counters = array();
        $user = JFactory::getUser();
        $user_id = JFactory::getApplication()->input->getInt('user_id', 0);

        if ($user_id != $user->id && 0 != $user_id) {
            return $counters;
        }

        $model = JModelLegacy::getInstance('Comments', 'FrontendModel');
        $counters['comments'] = $model->getUnreadCount();

        return $counters;
    }

    public function trackVisit($userId, $ip)
    {
        // 1. Register IP address.
        $this->registerIpForUser($userId, $ip);

        // 2. Register last visit.
        $this->registerLastVisitForUser($userId);
    }

    public function getRouteUpdateStatus()
    {
        return FactoryRoute::task('status.update&format=raw', false, -1);
    }

    protected function setLoggedIn($id, $status)
    {
        $this->registerLastVisitForUser($id);

        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->update('#__lovefactory_profiles')
            ->set('loggedin = ' . $dbo->quote($status))
//      ->set('validated = ' . $dbo->quote(1))
            ->where('user_id = ' . $dbo->quote($id));
        $dbo->setQuery($query);

        return $dbo->execute();
    }

    protected function getUserIdForUsername($username)
    {
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->select('u.id')
            ->from('#__users u')
            ->where('u.username = ' . $dbo->quote($username));
        $dbo->setQuery($query);

        return $dbo->loadResult();
    }

    protected function registerLastVisitForUser($id)
    {
        $dbo = JFactory::getDbo();
        $date = JFactory::getDate();

        $query = $dbo->getQuery(true)
            ->update('#__lovefactory_profiles')
            ->set('lastvisit = ' . $dbo->quote($date->toUnix()))
            ->where('user_id = ' . $dbo->quote($id));
        $dbo->setQuery($query);

        return $dbo->execute();
    }

    protected function registerIpForUser($userId, $ip)
    {
        $table = $this->getTable('Ip', 'Table');

        if (!$table->load(array('user_id' => $userId, 'ip' => $ip))) {
            $table->user_id = $userId;
            $table->ip = $ip;
        }

        $table->visits++;

        if (!$table->check()) {
            return false;
        }

        if (!$table->store()) {
            return false;
        }

        return true;
    }

    protected function registerProfileVisit($visitedId, $visitorId)
    {
        $table = $this->getTable('ProfileVisitor', 'Table');

        return $table->update($visitedId, $visitorId);
    }

    public function getError($i = null, $toString = true)
    {
        // Find the error
        if ($i === null) {
            // Default, return the last message
            $error = end($this->_errors);
        } elseif (!array_key_exists($i, $this->_errors)) {
            // If $i has been specified but does not exist, return false
            return false;
        } else {
            $error = $this->_errors[$i];
        }

        // Check if only the string is requested
        if ($error instanceof Exception && $toString) {
            return (string)$error;
        }

        return $error;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function setError($error)
    {
        array_push($this->_errors, $error);
    }

    /**
     * @return LoveFactoryTable
     */
    public function getTable($name = '', $prefix = 'Table', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    public function createProfileFromJoomlaUser(array $user = array())
    {
        $profile = JTable::getInstance('Profile', 'Table');
        $date = JFactory::getDate();

        $profile->save(array(
            'user_id' => $user['id'],
            'date' => $date->toSql(),
            'display_name' => $user['username'],
        ));

        return $profile;
    }
}
