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

namespace ThePhpFactory\LoveFactory\Security\Exceptions;

defined('_JEXEC') or die;

use Exception;

class Redirect extends \Exception
{
    private $redirect;

    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        if ('' === $message) {
            $message = \FactoryText::_($this->message);
        }

        parent::__construct($message, $code, $previous);
    }

    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;
    }

    public function getRedirect()
    {
        if (null === $this->redirect) {
            return \JMenu::getInstance('site')->getDefault()->link;
        }

        return $this->redirect;
    }
}
