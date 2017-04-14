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

jimport('joomla.plugin.plugin');

class plgSystemFactoryTokenAuth extends JPlugin
{
    public function __construct(&$subject, $config = array())
    {
        parent::__construct($subject, $config);

        $this->checkTable();
        $this->deleteExpiredTokens();
    }

    public function onAfterRoute()
    {
        // Initialise variables.
        $user = JFactory::getUser();
        $input = JFactory::getApplication()->input;

        // Get the token
        $tokenName = $this->params->get('name', 'token');
        $token = $input->get($tokenName, '', 'string');

        // Check if token is empty
        if (empty($token)) {
            return false;
        }

        // Find the token in database
        $record = $this->findTokenInDb($token);

        // Was the token found in database
        if (!$record) {
            return false;
        }

        JPluginHelper::importPlugin('user');

        // First logout currently logged in user,
        // only if it's not the user we want to authentificate
        if ($user->id && $user->id != $record->user_id) {
            $this->logOutCurrentUser($user);
        }

        // Login new user.
        $results = $this->logInUser($record->user_id);

        // If login was successful, delete token.
        if ($results[0] === true) {
            $this->deleteToken($record->id);
        }

        return true;
    }

    public function FactoryTokenAuthCreateToken($parameters)
    {
        // Check if user_id is provided
        if (!isset($parameters['user_id'])) {
            throw new Exception(JText::_('NO_USER_ID_PROVIDED'));
        }

        $date = JFactory::getDate();
        $dbo = JFactory::getDBO();
        $userId = intval($parameters['user_id']);
        $user = JFactory::getUser($userId);

        $expires_at = date('Y-m-d H:i:s', strtotime('+' . $this->params->get('valability', 30) . ' days', $date->toUnix()));
        $created_at = $date->toSql();

        // Check if user exists
        if (is_null($user->id)) {
            throw new Exception(JText::_('USER_WAS_NOT_FOUND'));
        }

        // Find if user has already a token
        $record = $this->findTokenForUser($userId);

        if (!$record) {
            // User doesn't have a token, create a new one
            $token = $this->createToken($userId);
            $query = $this->getQueryInsertToken($userId, $token, $created_at, $expires_at);
        } else {
            $query = $this->getQueryUpdateToken($userId, $expires_at);
            $token = $record->token;
        }

        $dbo->setQuery($query);

        if (!$dbo->execute()) {
            throw new Exception(JText::_('ERROR_UPDATING_DATABASE'));
        }

        return $this->params->get('name', 'token') . '=' . $token;
    }

    protected function checkTable()
    {
        /* @var $dbo JDatabaseDriver */
        $dbo = JFactory::getDBO();

        // Get the table
        $query = ' SHOW TABLES'
            . ' LIKE ' . $dbo->q($dbo->getPrefix() . 'factory_tokens_auth');
        $result = $dbo->setQuery($query)
            ->loadObject();

        // Table was found, whew...
        if ($result) {
            return true;
        }

        // Table was not found, create the table
        $query = ' CREATE TABLE #__factory_tokens_auth ('
            . '   `id` int(11) NOT NULL AUTO_INCREMENT,'
            . '   `user_id` int(11) NOT NULL,'
            . '   `token` varchar(255) NOT NULL,'
            . '   `created_at` datetime NOT NULL,'
            . '   `expires_at` datetime NOT NULL,'
            . '   PRIMARY KEY (`id`)'
            . ' ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';
        $dbo->setQuery($query);
        $dbo->execute();

        return true;
    }

    protected function deleteExpiredTokens()
    {
        $date = JFactory::getDate();
        $dbo = JFactory::getDBO();

        $query = ' DELETE'
            . ' FROM #__factory_tokens_auth'
            . ' WHERE expires_at <= ' . $dbo->q($date->toSql());
        $dbo->setQuery($query)
            ->execute();

        return true;
    }

    protected function createToken($userId)
    {
        $dirname = dirname(__FILE__);
        $stat = implode('', stat(__FILE__));
        $token = $userId . time() . rand(0, 99999999);

        return md5($dirname . $token) . md5($token . $stat);
    }

    protected function findTokenInDb($token)
    {
        $dbo = JFactory::getDbo();
        $date = JFactory::getDate();

        $query = $dbo->getQuery(true)
            ->select('t.*')
            ->from($dbo->qn('#__factory_tokens_auth') . ' AS t')
            ->where($dbo->qn('t.token') . ' = ' . $dbo->q($token))
            ->where($dbo->qn('t.expires_at') . ' > ' . $dbo->q($date->toSql()));

        return $dbo->setQuery($query)
            ->loadObject();
    }

    protected function logOutCurrentUser($user)
    {
        $app = JFactory::getApplication();
        $dispatcher = JEventDispatcher::getInstance();

        $parameters['username'] = $user->get('username');
        $parameters['id'] = $user->get('id');
        $clientid = $app->getClientId();

        // Log out user
        $results = $dispatcher->trigger('onUserLogout', array($parameters, array('clientid' => array($clientid))));

        // Delete all session records for user
        $table = JTable::getInstance('session');
        $table->destroy($user->id, array($clientid));

        // Delete cookies
        foreach ($_COOKIE as $key => $value) {
            setcookie($key, $value, time() - 10000, '/');
        }

        // Reload page
        $app->redirect($_SERVER['REQUEST_URI']);

        return true;
    }

    protected function logInUser($userId)
    {
        $dbo = JFactory::getDbo();
        $dispatcher = JEventDispatcher::getInstance();

        $query = $dbo->getQuery(true)
            ->select('u.*')
            ->from($dbo->qn('#__users') . ' AS u')
            ->where($dbo->qn('u.id') . ' = ' . $dbo->q($userId));
        $user_found = $dbo->setQuery($query)
            ->loadAssoc();

        $options = array('autoregister' => false, 'action' => 'core.login.site');
        $results = $dispatcher->trigger('onUserLogin', array($user_found, $options));

        return $results;
    }

    protected function deleteToken($id)
    {
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->delete($dbo->qn('#__factory_tokens_auth'))
            ->where($dbo->qn('id') . ' = ' . $dbo->q($id));
        $dbo->setQuery($query)
            ->execute();

        return true;
    }

    protected function findTokenForUser($userId)
    {
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->select('t.*')
            ->from($dbo->qn('#__factory_tokens_auth') . ' AS t')
            ->where($dbo->qn('t.user_id') . ' = ' . $dbo->q($userId));

        $record = $dbo->setQuery($query)
            ->loadObject();

        return $record;
    }

    protected function getQueryInsertToken($userId, $token, $created_at, $expires_at)
    {
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->insert($dbo->qn('#__factory_tokens_auth'))
            ->set($dbo->qn('user_id') . ' = ' . $dbo->q($userId))
            ->set($dbo->qn('token') . ' = ' . $dbo->q($token))
            ->set($dbo->qn('created_at') . ' = ' . $dbo->q($created_at))
            ->set($dbo->qn('expires_at') . ' = ' . $dbo->q($expires_at));

        return $query;
    }

    protected function getQueryUpdateToken($userId, $expires_at)
    {
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->update($dbo->qn('#__factory_tokens_auth'))
            ->set($dbo->qn('expires_at') . ' = ' . $dbo->q($expires_at))
            ->where($dbo->qn('user_id') . ' = ' . $dbo->q($userId));

        return $query;
    }
}
