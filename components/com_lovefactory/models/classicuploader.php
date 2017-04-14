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

jimport('joomla.application.component.model');

class FrontendModelClassicUploader extends FactoryModel
{
    public function getTypeSelect()
    {
        $type = JFactory::getApplication()->input->getCmd('type');

        $options = array(
            'public' => JText::_('CLASSIC_UPLOADER_TYPE_PUBLIC'),
            'friends' => JText::_('CLASSIC_UPLOADER_TYPE_FRIENDS'),
            'private' => JText::_('CLASSIC_UPLOADER_TYPE_PRIVATE'),
        );

        $select = JHtml::_('select.genericlist', $options, 'type', '', 'value', 'text', $type);

        return $select;
    }

    public function getFormAction()
    {
        return JRoute::_('index.php?option=com_lovefactory&controller=gallery&task=classicupload', true);
    }

    public function getUploadLimit()
    {
        $model = JModelLegacy::getInstance('MyGallery', 'FrontendModel');
        $user = JFactory::getUser();

        $allowed = $model->getMaximumAllowedItems('photos', $user);
        $total = $model->getTotalItems('photos', $user);

        return $allowed == -1 ? -1 : $allowed - $total;
    }

    public function getClassicUploader()
    {
        $settings = new LoveFactorySettings();

        return $settings->enable_classic_uploader;
    }
}
