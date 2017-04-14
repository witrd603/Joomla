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

JFormHelper::loadFieldClass('List');

class JFormFieldLoveFactoryInfobar extends JFormFieldList
{
    public $type = 'LoveFactoryInfobar';

    protected function getOptions()
    {
        $options = parent::getOptions();
        $params = JComponentHelper::getParams('com_lovefactory');

        if (1 != $params->get('infobar.location', 1)) {
            unset($options[2], $options[3]);
        } else {
            unset($options[1]);
        }

        return $options;
    }
}
