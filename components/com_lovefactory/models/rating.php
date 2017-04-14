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

class FrontendModelRating extends FactoryModel
{
    public function add()
    {
        $user = JFactory::getUser();
        $id = JFactory::getApplication()->input->getInt('user_id');
        $vote = JFactory::getApplication()->input->getInt('rating');
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        // Check if it's my profile
        if ($user->id == $id) {
            $this->setError(FactoryText::_('rating_task_add_error_vote_self'));
            return false;
        }

        // Check if user exists
        $profile = $this->getTable('profile', 'Table');
        $profile->load($id);

        if ($profile->_is_new) {
            $this->setError(FactoryText::_('rating_task_add_error_user_not_found'));
            return false;
        }

        // Check if the vote is valid
        if (1 > $vote || 10 < $vote) {
            $this->setError(FactoryText::_('rating_task_add_error_vote_not_valid'));
            return false;
        }

        // Check if user already rated this profile.
        $model = JModelLegacy::getInstance('Ratings', 'FrontendModel');
        $rating = $model->getMyRatingForUser($id);

        if (!$settings->enable_rating_update && false !== $rating) {
            $this->setError(FactoryText::_('ratings_task_add_error_already_voted'));
            return false;
        }

        $newVote = false === $rating;

        // Add new rating
        $date = JFactory::getDate();

        $rating = $this->getTable('rating');
        if (!$newVote) {
            $rating->load(array('sender_id' => $user->id, 'receiver_id' => $id));
        }

        $rating->receiver_id = $id;
        $rating->sender_id = $user->id;
        $rating->date = $date->toSql();
        $rating->rating = $vote;

        $rating->store();

        // Calculate the new total rating
        $profile->rating = $this->calculateProfileRating($profile->user_id);

        if ($newVote) {
            $profile->votes++;
        }

        $profile->store();

        // Register activity.
        JEventDispatcher::getInstance()->trigger('onLoveFactoryRatingReceived', array(
            'com_lovefactory.rating_received',
            $rating,
            $newVote
        ));

        return true;
    }

    protected function calculateProfileRating($userId)
    {
        $dbo = $this->getDbo();

        $query = $dbo->getQuery(true)
            ->select('AVG(r.rating)')
            ->from('#__lovefactory_ratings r')
            ->where('r.receiver_id = ' . $dbo->q($userId));

        return $dbo->setQuery($query)
            ->loadResult();
    }
}
