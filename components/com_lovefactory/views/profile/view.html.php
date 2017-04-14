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

use ThePhpFactory\LoveFactory\Factory;

class FrontendViewProfile extends FactoryView
{
    protected
        $get = array(
        'profile',
        'page',
        'renderer',
        'settings',
        'visitor',
        'ratings',
        'friendship',
    ),
        $css = array('icons'),
        $js = array('jquery.autosize'),
        $behaviors = array('factoryTooltip', 'factoryAjaxAction', 'factoryjQueryCookie'),
        $javascriptVariables = array('routeUpdateStatus'),
        $routes = array(
        'ratingAdd/task/rating.add&format=raw',
        'ratingUpdate/view/profile&format=raw'
    );

    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    public function display($tpl = null)
    {
        $key = LoveFactoryApplication::getInstance()->getSettings('gmaps_api_key', '');
        if ($key) {
            JHtml::script('https://maps.googleapis.com/maps/api/js?key=' . $key);
        }

        JHtml::script('components/com_lovefactory/assets/js/modules.js');

        // Check if viewing another user profile, or own profile.
        $user = JFactory::getUser();
        $profileId = JFactory::getApplication()->input->getInt('user_id', $user->id);

        if (!$user->guest && (int)$user->id !== (int)$profileId) {
            $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('profile_access');

            try {
                $restriction->isAllowed($user->id);
            } catch (Exception $e) {
                JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
                JFactory::getApplication()->redirect(FactoryRoute::view('memberships'));
            }
        }

        return parent::display($tpl);
    }

    protected function getRenderer()
    {
        return Factory::buildPageRenderer('viewable');
    }

    protected function getSettings()
    {
        return LoveFactoryApplication::getInstance()->getSettings();
    }
}
