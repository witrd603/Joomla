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

class FrontendControllerInfoBar extends FrontendController
{
    public function update()
    {
        $model = $this->getModel('Infobar', 'FrontendModel');

        $this->renderJson($model->update());

        return true;
    }

    public function close()
    {
        $model = $this->getModel('Infobar', 'FrontendModel');
        $model->close();

        return true;
    }
}
