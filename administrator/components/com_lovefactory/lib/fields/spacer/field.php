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

class LoveFactoryFieldSpacer extends LoveFactoryField
{
    public function renderInputView()
    {
        $height = $this->params->get('height', 20);

        return '<div style="height: ' . $height . 'px;"></div>';
    }

    public function renderInputEdit()
    {
        return $this->renderInputView();
    }

    public function renderInputSearch()
    {
        return $this->renderInputView();
    }

    public function showLabel()
    {
        return false;
    }
}
