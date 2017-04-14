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

class FrontendController extends JControllerLegacy
{
    public function __construct(array $config = array())
    {
        parent::__construct($config);

        $this->checkSystemPluginIsEnabled();
        $this->doMaintenance();
        $this->trackUser();
    }

    protected function isAjaxRequest()
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
    }

    protected function checkMembershipRestrictionRedirection($model, &$response)
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if ($model->getState('membership_restriction_error', false) && $settings->invalid_membership_action) {
            $response['redirect'] = FactoryRoute::view('memberships');

            if ($this->isAjaxRequest()) {
                $flash = FactoryFlashMessage::getInstance();
                $flash->setMessage($response['message']);
                $flash->setWarning($response['error']);
            }
        }
    }

    protected function renderJson($data)
    {
        if (!JDEBUG) {
            // Clear the buffer
            ob_end_clean();
        }

        // Set headers
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');

        // Output data
        echo json_encode($data);

        // Exit
        jexit();
    }

    private function checkSystemPluginIsEnabled()
    {
        // Check if system plugin is enabled.
        $extension = JTable::getInstance('Extension');
        $result = $extension->load(array(
            'type' => 'plugin',
            'element' => 'lovefactory',
            'folder' => 'system',
        ));

        if (!$result || !$extension->enabled) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_LOVEFACTORY_SYSTEM_PLUGIN_WARNING'));
        }
    }

    private function doMaintenance()
    {
        JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_lovefactory/models');

        $model = $this->getModel('Maintenance');
        $model->perform();
    }

    private function trackUser()
    {
        $user = JFactory::getUser();

        if (!$user->guest) {
            $ip = $_SERVER['REMOTE_ADDR'];

            JLoader::register('FrontendModelProfile', JPATH_SITE . '/components/com_lovefactory/models/profile.php');
            $model = new FrontendModelProfile();

            $model->trackVisit($user->id, $ip);
        }

        return true;
    }
}
