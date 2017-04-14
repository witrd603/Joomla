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

define('_JEXEC', 1);
defined('_JEXEC') or die;

if (!defined('_JDEFINES')) {
    define('JPATH_BASE', realpath(__DIR__ . '/../../'));
    require_once JPATH_BASE . '/includes/defines.php';
}

require_once JPATH_BASE . '/includes/framework.php';

$app = JFactory::getApplication('site');

if (JDEBUG) {
    JPluginHelper::importPlugin('system', 'lovefactorynotifications');
} else {
    JPluginHelper::importPlugin('system');
}

defined('JPATH_COMPONENT') or define('JPATH_COMPONENT', JPATH_SITE . '/components/com_lovefactory');
define('LOVEFACTORY_COMPONENT_PATH', dirname(__FILE__));
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'lib' . DS . 'application.php';

// Initialise variables.
jimport('joomla.application.component.model');
JModelLegacy::addIncludePath(JPATH_SITE . DS . 'components' . DS . 'com_lovefactory' . DS . 'models', 'FrontendModel');
JLoader::register('Logger', JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/logger.php');

$logger = new Logger();

$model = JModelLegacy::getInstance('CronJob', 'FrontendModel', array(
    'logger' => $logger,
));

// Execute Cron Job actions.
$model->execute(JFactory::getApplication()->input->getString('pass'));
