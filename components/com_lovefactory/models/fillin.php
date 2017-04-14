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

class FrontendModelFillin extends FactoryModel
{
    protected $renderErrorsIndividual = true;

    public function getPage($page = 'profile_fillin', $mode = 'edit')
    {
        $page = LoveFactoryPage::getInstance($page, $mode, array(
            'renderErrorsIndividual' => $this->renderErrorsIndividual
        ));

        $session = JFactory::getSession();
        $context = 'com_lovefactory.profile.fillin.data';
        $request = $session->get($context, null);

        $profile = JTable::getInstance('Profile', 'Table');
        $profile->load(JFactory::getUser()->id);

        $page->bind($profile);

        if (!is_null($request)) {
            $page->bind($request);
            $session->set($context, null);
        }

        return $page;
    }

    public function fillin($data)
    {
        $user = JFactory::getUser();

        $page = $this->getPage();
        $page->bind($data);

        $valid = $page->validate();

        if (!$valid) {
            $this->setState('filtered.data', $page->getFilteredData());

            if (!$this->renderErrorsIndividual) {
                $this->setError(implode('<br />', $page->getErrors()));
            } else {
                $this->setError(FactoryText::plural('page_saving_error', count($page->getErrors())));
            }

            return false;
        }

        $profile = $this->getTable('Profile', 'Table');
        $profile->load($user->id);

        $profile->user_id = $user->id;
        $profile->validated = 1;
        $profile->filled = 1;

        $profile->bindFromRequest($page->convertDataToProfile());
        $profile->addDefaultInTable();
        $profile->createUserFolder();

        $dispatcher = JEventDispatcher::getInstance();
        $dispatcher->trigger('onLoveFactoryProfileBeforeSave', array(
            'com_lovefactory.profile.save.before',
            $profile
        ));

        if (!$profile->store(false, true)) {
            $this->setError($profile->getError());
            return false;
        }

        $page->postProfileSave($profile);

        // Update Google Maps Location based on Location fields.
        $model = JModelLegacy::getInstance('Edit', 'FrontendModel');
        $model->updateLocation($profile);

        // Update user as logged in.
        $model = JModelLegacy::getInstance('Profile', 'FrontendModel');
        $model->login($user->username);

        return true;
    }
}
