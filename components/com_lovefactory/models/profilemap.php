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

class FrontendModelProfileMap extends FactoryModel
{
    public function getProfile($user_id = null)
    {
        static $profiles = array();

        $user = JFactory::getUser();
        if (is_null($user_id)) {
            $user_id = JFactory::getApplication()->input->getInt('user_id', JFactory::getUser()->id);
        }

        if (!isset($profiles[$user_id])) {
            $dbo = $this->getDbo();
            $query = $dbo->getQuery(true)
                ->select('p.*')
                ->from('#__lovefactory_profiles p')
                ->where('p.user_id = ' . $dbo->quote($user_id));

            // Select the username.
            $query->select('u.username')
                ->leftJoin('#__users u ON u.id = p.user_id');

            // Select if users are friends.
            $query->select('f.id AS is_friend')
                ->leftJoin('#__lovefactory_friends f ON ((f.sender_id = p.user_id AND f.receiver_id = ' . $dbo->quote($user->id) . ') OR (f.receiver_id = p.user_id AND f.sender_id = ' . $dbo->quote($user->id) . ')) AND f.pending = ' . $dbo->quote(0));

            // Select if user is blocked.
            $query->select('b.id AS blocked')
                ->leftJoin('#__lovefactory_blacklist b ON b.sender_id = ' . $dbo->quote($user->id) . ' AND b.receiver_id = p.user_id');

            foreach ($this->getPage()->getFields(false) as $field) {
                $field->addQueryView($query);
            }

            $result = $dbo->setQuery($query)
                ->loadObject('TableProfile');

            $profiles[$user_id] = $result;
        }

        return $profiles[$user_id];
    }

    public function getRenderer()
    {
        $renderer = LoveFactoryPageRenderer::getInstance();

        return $renderer;
    }

    public function getPage($page = 'profile_map', $mode = 'view')
    {
        $page = LoveFactoryPage::getInstance($page, $mode);

        return $page;
    }
}
