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

require_once JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/application.php';
require_once JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/methods.php';
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_lovefactory/models');

LoveFactoryApplication::getInstance();

$model = JModelLegacy::getInstance('Payment', 'FrontendModel');
$model->notify(JFactory::getApplication()->input->getInt('gateway'));
