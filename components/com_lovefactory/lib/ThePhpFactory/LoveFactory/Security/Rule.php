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

abstract class Rule
{
    private $name = null;

    public function getName()
    {
        if (null === $this->name) {
            $rClass = new \ReflectionClass($this);
            $this->name = $rClass->getShortName();
        }

        return $this->name;
    }

    abstract public function authorize(\JUser $user);
}
