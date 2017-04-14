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

JFormHelper::loadFieldType('Text');

class JFormFieldLoveFactoryReportType extends JFormFieldText
{
    public $type = 'LoveFactoryReportType';

    protected function getInput()
    {
        $element = $this->form->getValue('element');
        $type = $this->value;

        $this->value = FactoryText::_('report_type_' . $element . ('' == $type ? '' : '_' . $type));

        $html = parent::getInput();

        return $html;
    }
}
