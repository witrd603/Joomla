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

jimport('joomla.application.component.modellist');

class FrontendModelComments extends LoveFactoryFrontendModelList
{
    public function getViewItemComments()
    {
        JLoader::register('FrontendViewItemComments', LoveFactoryApplication::getInstance()->getPath('component_site') . DS . 'views' . DS . 'itemcomments' . DS . 'view.html.php');

        $view = new FrontendViewItemComments();
        $model = $this->getModel();

        $view->setModel($model, true);

        return $view;
    }

    public function getUserId()
    {
        $userId = JFactory::getApplication()->input->getInt('user_id', JFactory::getUser()->id);

        return $userId;
    }

    public function getUnreadCount()
    {
        return $this->getModel()->getUnreadCount();
    }

    protected function getModel()
    {
        $model = JModelLegacy::getInstance('ItemComments', 'FrontendModel');

        $model->setItemType('Profile');
        $model->setItemId($this->getUserId());

        return $model;
    }
}
