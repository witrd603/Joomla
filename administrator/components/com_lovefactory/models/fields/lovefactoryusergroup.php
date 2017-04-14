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

JFormHelper::loadFieldType('UserGroup');

class JFormFieldLoveFactoryUserGroup extends JFormFieldUsergroup
{
    public $type = 'LoveFactoryUserGroup';

    protected function getInput()
    {
        $values = new JRegistry($this->value);
        $this->value = array_values($values->toArray());

        return parent::getInput();
    }
}
