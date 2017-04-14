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

class FrontendModelGroupThreadEdit extends FactoryModel
{
    public function getGroup()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $table = $this->getTable('Group');
        $table->load($id);

        if (!$table->userIsMember() && !$table->isMyGroup()) {
            return false;
        }

        return $table;
    }

    public function getForm()
    {
        $file = JPATH_SITE . '/components/com_lovefactory/models/forms/groupthread.xml';
        $form = JForm::getInstance('com_lovefactory.groupthread', $file, array('control' => 'data'));

        LoveFactoryHelper::addFormLabels($form);

        return $form;
    }
}
