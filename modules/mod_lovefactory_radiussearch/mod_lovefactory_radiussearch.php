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
$result = $extension->find(array('type' => 'component', 'element' => 'com_lovefactory'));

// Check if Love Factory is installed
if (!$result) {
  echo JText::_('MOD_LOVEFACTORY_INSTALL_LOVE_FACTORY');
  return false;
}

require_once JPATH_SITE . '/components/com_lovefactory/vendor/autoload.php';
require_once(JPATH_SITE . '/components/com_lovefactory/controllers/module.php');

$input = JFactory::getApplication()->input;
if ('com_lovefactory' == $input->getCmd('option') && 'radiussearch' == $input->getCmd('view')) {
  return false;
}

JLoader::register('FrontendViewRadiusSearch', JPATH_SITE . '/components/com_lovefactory/views/radiussearch/view.html.php');

$controller = new FrontendControllerModule();
$controller->render($module, $params);
