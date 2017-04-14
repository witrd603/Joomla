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

class FrontendModelGroupEdit extends FactoryModel
{
    protected $item;

    public function getItem()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $table = $this->getTable('Group');
        $user = JFactory::getUser();
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $app = JFactory::getApplication();

        if (!$id || !$table->load($id)) {
            // Check if group creation is allowed
            if (!$settings->groups_allow_users_create) {
                $app->redirect(FactoryRoute::view('groups'), FactoryText::_('groupedit_group_create_not_allowed'));
            }

            // Check if user is allowed to create a new group
            $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('groups_create');
            try {
                $restriction->isAllowed($user->id);
            } catch (Exception $e) {
                $this->setError($e->getMessage());
                $this->setState('membership_restriction_error', true);

                $app->redirect(FactoryRoute::view('memberships'), $e->getMessage());

                return false;
            }
        } else {
            // Check if group belongs to user to edit.
            if ($table->user_id != $user->id) {
                $app->redirect(FactoryRoute::view('groups'), FactoryText::_('groupedit_edit_not_allowed'));
            }
        }

        $this->item = $table;

        return $table;
    }

    public function getForm()
    {
        $file = JPATH_SITE . '/components/com_lovefactory/models/forms/group.xml';
        $form = JForm::getInstance('com_lovefactory.group', $file, array('control' => 'group'));

        $form->bind($this->item);

        LoveFactoryHelper::addFormLabels($form);

        return $form;
    }
}
