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

// Find the Love Factory extension.
$extension = JTable::getInstance('extension');
$result    = $extension->find(array('type' => 'component', 'element' => 'com_lovefactory'));

// Check if Love Factory is installed
if (!$result) {
  echo JText::_('MOD_LOVEFACTORY_INSTALL_LOVE_FACTORY');
  return false;
}

// Get the controller
require_once(JPATH_SITE.DS.'components'.DS.'com_lovefactory'.DS.'controllers'.DS.'module.php');
$controller = new FrontendControllerModule();

// Display the module
$controller->render($module, $params);
