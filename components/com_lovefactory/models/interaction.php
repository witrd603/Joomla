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

class FrontendModelInteraction extends FactoryModel
{
    public function send()
    {
        // Initialise variables.
        $user = JFactory::getUser();
        $id = JFactory::getApplication()->input->getInt('user_id');
        $type_id = JFactory::getApplication()->input->getInt('type_id');

        // Check if interaction is enabled.
        if (!$this->isInteractionEnabled($type_id)) {
            return false;
        }

        // Check if sending interaction to self.
        if ($id == $user->id) {
            $this->setError(FactoryText::_('interaction_task_send_error_cannot_send_to_self'));
            return false;
        }

        // Check if interactions limit reached.
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('interactions');

        try {
            $restriction->isAllowed($user->id);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            $this->setState('membership_restriction_error', true);

            return false;
        }

        // Check if the user is blocked.
        $model = JModelLegacy::getInstance('Blacklist', 'FrontendModel');
        $isBlocked = $model->isBlacklisted($user->id, $id);
        if ($isBlocked) {
            $this->setError($model->getError());
            return false;
        }

        // Check user is allowed to interact with members of same sex.
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('same_gender_interaction');

        try {
            $restriction->isAllowed($user->id, $id);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            $this->setState('membership_restriction_error', true);

            return false;
        }

        // Send the interaction
        $table = $this->getTable('Interaction');

        $table->receiver_id = $id;
        $table->sender_id = $user->id;
        $table->date = JFactory::getDate()->toSql();
        $table->type_id = $type_id;

        $table->store();

        // Update statistics.
        $this->updateSentInteractions($user->id);

        // Get token for token authentication.
        $dispatcher = JEventDispatcher::getInstance();
        $results = $dispatcher->trigger('FactoryTokenAuthCreateToken', array('parameters' => array('user_id' => $table->receiver_id)));
        $token = $results ? '&' . $results[0] : '';

        JEventDispatcher::getInstance()->trigger('onLoveFactoryInteractionSent', array(
            'com_lovefactory.interaction_sent.after', $table, $token
        ));

        return true;
    }

    public function respond($id)
    {
        // Initialise variables.
        $user = JFactory::getUser();
        $interaction = $this->getTable('Interaction');

        // Get original interaction.
        $interaction->load($id);

        // Check if interaction is enabled.
        if (!$this->isInteractionEnabled($interaction->type_id)) {
            return false;
        }

        // Check if user received the interaction.
        if ($interaction->receiver_id != $user->id) {
            $this->setError(FactoryText::_('interaction_task_respond_error_interaction_not_found'));
            return false;
        }

        // Check if interactions limit reached.
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('interactions');

        try {
            $restriction->isAllowed($user->id);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            $this->setState('membership_restriction_error', true);

            return false;
        }

        // Check if the user is blocked.
        $model = JModelLegacy::getInstance('Blacklist', 'FrontendModel');
        $isBlocked = $model->isBlacklisted($user->id, $interaction->sender_id);
        if ($isBlocked) {
            $this->setError($model->getError());
            return false;
        }

        // Check user is allowed to interact with members of same sex.
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('same_gender_interaction');

        try {
            $restriction->isAllowed($user->id, $id);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            $this->setState('membership_restriction_error', true);

            return false;
        }

        // Respond to interaction.
        $table = $this->getTable('Interaction');

        $table->receiver_id = $interaction->sender_id;
        $table->sender_id = $interaction->receiver_id;
        $table->date = JFactory::getDate()->toSql();
        $table->type_id = $interaction->type_id + 1;

        $table->store();

        // Update interaction response
        $interaction->responded = 1;
        $interaction->store();

        // Update statistics.
        $this->updateSentInteractions($user->id);

        $dispatcher = JEventDispatcher::getInstance();
        $results = $dispatcher->trigger('FactoryTokenAuthCreateToken', array('parameters' => array('user_id' => $table->receiver_id)));
        $token = $results ? '&' . $results[0] : '';

        JEventDispatcher::getInstance()->trigger('onLoveFactoryInteractionSent', array(
            'com_lovefactory.interaction_sent.after', $table, $token
        ));

        return true;
    }

    protected function isInteractionEnabled($type)
    {
        // Initialise variables.
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $types = array(1 => 'kiss', 5 => 'wink', 3 => 'hug');

        // Check if interactions are enabled.
        if (!$settings->enable_interactions) {
            $this->setError(FactoryText::_('interaction_task_send_error_interactions_not_enabled'));
            return false;
        }

        // Check if specific interaction is enabled.
        if (!$settings->{'enable_interaction_' . $types[$type]}) {
            $this->setError(FactoryText::_('interaction_task_send_error_interaction_not_enabled'));
            return false;
        }

        return true;
    }

    protected function updateSentInteractions($userId)
    {
        $table = $this->getTable('StatisticsPerDay');

        return $table->updateInteractions($userId);
    }
}
