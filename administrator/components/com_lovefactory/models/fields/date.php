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

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');
JFormHelper::loadFieldClass('text');

class JFormFieldDate extends JFormFieldText
{
    public $type = 'Date';

    protected function getInput()
    {
        $this->value = JHtml::date($this->value, 'Y-m-d H:i:s');

        return parent::getInput();
    }
}
