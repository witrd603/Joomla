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

namespace ThePhpFactory\LoveFactory\Security;

defined('_JEXEC') or die;

use ThePhpFactory\LoveFactory\Security\Exceptions\Redirect;

class Firewall
{
    public function __construct(array $rules = array())
    {
        $this->rules = $rules;
    }

    public function authorize(\JUser $user, \JInput $input)
    {
        $rules = $this->getRules($input);

//    var_dump($rules);
//    die;

        if (!$rules) {
            return true;
        }

        foreach ($rules as $rule) {
            $class = '\\ThePhpFactory\\LoveFactory\\Security\\Rules\\' . $rule;
            $rule = new $class;

            try {
                /** @var $rule Rule */
                $rule->authorize($user);
            } catch (Redirect $e) {
                $this->redirect($e->getMessage(), $e->getRedirect());
            }
        }

        return true;
    }

    private function redirect($message, $url)
    {
        if ($this->isAjaxRequest()) {
            if (!JDEBUG) {
                ob_end_clean();
            }

            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/json');

            // Output data
            echo json_encode(array(
                'status' => 0,
                'message' => $message,
                'error' => $message,
                'redirect' => $url,
            ));

            // Exit
            jexit();
        }

        $app = \JFactory::getApplication();

        $app->enqueueMessage($message, 'error');
        $app->redirect($url);
    }

    private function getRules(\JInput $input)
    {
        $controller = $input->getCmd('controller');
        $task = $input->getCmd('task');
        $view = $input->getCmd('view');

        if (false !== strpos($task, '.')) {
            list ($controller, $task) = explode('.', $task);
        }

        $rules = array();

        if (null !== $controller) {
            if (isset($this->rules['task'][$controller . '.' . $task])) {
                $rules = $this->rules['task'][$controller . '.' . $task];
            } elseif (isset($this->rules['controller'][$controller])) {
                $rules = $this->rules['controller'][$controller];
            }
        } elseif (isset($this->rules['view'][$view])) {
            $rules = $this->rules['view'][$view];
        }

        // Special case for own profile.
        if ('profile' === $view) {
            $user = \JFactory::getUser();
            $id = $input->getInt('user_id', $user->id);

            if ((int)$user->id && (int)$user->id === (int)$id) {
                $rules = array();
            }
        }

        return $rules;
    }

    private function isAjaxRequest()
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
    }
}
