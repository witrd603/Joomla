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

class TableToken extends JTable
{
    var $id = null;
    var $user_id = null;
    var $token = null;
    var $created_at = null;
    var $expires_at = null;

    function __construct(&$db)
    {
        parent::__construct('#__lovefactory_tokens', 'id', $db);
    }

    function createTokenForUser($user_id)
    {
        $date = JFactory::getDate();

        $query = ' SELECT t.*'
            . ' FROM #__lovefactory_tokens t'
            . ' WHERE t.user_id = ' . $user_id;
        $this->_db->setQuery($query);
        $record = $this->_db->loadObject();

        if (!$record) {
            $dirname = dirname(__FILE__);
            $stat = implode('', stat(__FILE__));
            $token = $user_id . time() . rand(0, 99999999);

            $newToken = md5($dirname . $token) . md5($token . $stat);

            $this->user_id = $user_id;
            $this->token = $newToken;
            $this->created_at = $date->toSql();

        } else {
            $this->bind($record);
        }

        $this->expires_at = date('Y-m-d H:i:s', strtotime('+30 days', $date->toUnix()));

        $this->store();

        return $this->token;
    }

    function validateTokenLogin($token)
    {
        $date = JFactory::getDate();

        // Find token
        $query = ' SELECT t.*'
            . ' FROM #__lovefactory_tokens t'
            . ' WHERE t.token = "' . $this->_db->getEscaped($token) . '"'
            . ' AND t.expires_at > "' . $date->toSql() . '"';
        $this->_db->setQuery($query);
        $record = $this->_db->loadObject();

        // If token was not found, abort
        if (!$record) {
            return false;
        }

        $user = JFactory::getUser();
        $app = JFactory::getApplication();

        // Get the user that needs to be logged in
        $query = 'SELECT * FROM #__users WHERE id = ' . $record->user_id;
        $this->_db->setQuery($query);
        $result = $this->_db->loadAssocList();

        JPluginHelper::importPlugin('user');
        $dispatcher = JEventDispatcher::getInstance();

        $options = array();
        $parameters = array();

        $options['autoregister'] = false;

        // Log out other users
        if ($user->id) {
            if ($user->id == $result[0]['id']) {
                return true;
            }

            $parameters['username'] = $user->get('username');
            $parameters['id'] = $user->get('id');

            $clientid = array('clientid' => array($app->getClientId()));

            $results = $dispatcher->trigger('onLogoutUser', array($parameters, $clientid));

            $table = &JTable::getInstance('session');
            $table->destroy($user->id, array($app->getClientId()));

            foreach ($_COOKIE as $key => $value) {
                setcookie($key, $value, time() - 10000, '/');
            }

            $app->redirect($_SERVER['REQUEST_URI']);
        }

        // Log in requested user
        $results = $dispatcher->trigger('onLoginUser', array($result[0], $options));

        // Delete token
        $query = ' DELETE'
            . ' FROM #__lovefactory_tokens'
            . ' WHERE id = ' . $record->id;
        $this->_db->setQuery($query);
        $this->_db->execute();

        return true;
    }
}
