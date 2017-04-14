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

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

if (file_exists(JPATH_ADMINISTRATOR . '/components/com_lovefactory/migrations.php') &&
    'migration' !== JFactory::getApplication()->input->getWord('controller')
) {
    require_once JPATH_ADMINISTRATOR . '/components/com_lovefactory/migrations.php';
}

$user = JFactory::getUser();

require_once JPATH_COMPONENT . DS . 'controller.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'lib' . DS . 'application.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'lib' . DS . 'methods.php';

$input = JFactory::getApplication()->input;
$task = $input->getCmd('task');
$controller = $input->getCmd('controller');

if ('' === $controller && false !== strpos($task, '.')) {
    list($controller, $task) = explode('.', $task);

    JFactory::getApplication()->input->set('controller', $controller);
    JFactory::getApplication()->input->set('task', $task);
}

if ($controller = JFactory::getApplication()->input->getWord('controller')) {
    require_once(JPATH_COMPONENT . DS . 'controllers' . DS . $controller . '.php');
}

// Check if system plugin is enabled.
$extension = JTable::getInstance('Extension');
$result = $extension->load(array('type' => 'plugin', 'element' => 'lovefactory', 'folder' => 'system'));
if (!$result || !$extension->enabled) {
    JFactory::getApplication()->enqueueMessage(JText::_('COM_LOVEFACTORY_SYSTEM_PLUGIN_WARNING'), 'error');
}

JLoader::register('LoveFactoryHelper', JPATH_COMPONENT_ADMINISTRATOR . '/lib/helper.php');
LoveFactoryHelper::addSubmenu();

$classname = 'BackendController' . $controller;
$controller = new $classname();
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
$controller->redirect();
