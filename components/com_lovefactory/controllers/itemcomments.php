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

class FrontendControllerItemComments extends FrontendController
{
    public function render($type, $id)
    {
        $view = $this->getView('ItemComments', 'html', 'FrontendView');
        $model = $this->getModel('ItemComments', 'FrontendModel');
        $view->setModel($model, true);

        $model->setItemType($type);
        $model->setItemId($id);

        $view->display();
    }
}
