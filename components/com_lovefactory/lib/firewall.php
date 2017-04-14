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

use \ThePhpFactory\LoveFactory\Security\Firewall;

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

require_once JPATH_COMPONENT_ADMINISTRATOR . '/lib/application.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/lib/methods.php';

JLoader::register('FrontendController', JPATH_SITE . '/components/com_lovefactory/controller.php');
JLoader::register('LoveFactoryHelper', JPATH_COMPONENT_ADMINISTRATOR . '/lib/helper.php');

$app = JFactory::getApplication();
$user = JFactory::getUser();
$input = $app->input;

$controller = $input->getCmd('controller');
$task = $input->getCmd('task');

if (null !== $controller && null !== $task) {
    $input->set('task', $controller . '.' . $task);
}

$defaultRules = '{"view":{"activity":["ActivityEnabled","ActivityPrivacy"],"advanced":["Filled"],"approvals":["Logged"],"blocked":["Logged"],"comments":["CommentsEnabled","CommentsPrivacy"],"createprofile":["CreateProfile"],"fillin":["Logged","NotFilled"],"myfriends":["FriendsEnabled","Logged"],"friends":["FriendsEnabled","Logged"],"requests":["FriendsEnabled","Logged"],"group":["GroupsEnabled","Logged"],"groupbanned":["GroupsEnabled","Logged"],"groupedit":["GroupsEnabled","Logged"],"groupmembers":["GroupsEnabled","Logged"],"groups":["GroupsEnabled","Logged"],"groupthread":["GroupsEnabled","Logged"],"groupthreadedit":["GroupsEnabled","Logged"],"groupthreadeds":["GroupsEnabled","Logged"],"inbox":["MessagesEnabled","Logged"],"interactions":["InteractionsEnabled","Logged"],"invoice":["Logged"],"invoices":["Logged"],"membershipbuy":["Logged"],"membersmap":["GoogleMapsEnabled","MembersMapEnabled"],"message":["MessagesEnabled","Logged"],"compose":["MessagesEnabled","Logged"],"mymembership":["Logged"],"outbox":["MessagesEnabled","Logged"],"radiussearch":["GoogleMapsEnabled","RadiusSearchEnabled"],"myrelationship":["RelationshipsEnabled","Logged"],"settings":["Logged"],"signup":["Guest"],"topfriends":["FriendsEnabled","TopFriendsEnabled"]},"controller":{"blacklist":["Logged"]}}';

$params = JComponentHelper::getParams('com_lovefactory');
$rules = $params->get('firewall.rules', $defaultRules);

$registry = new \Joomla\Registry\Registry($rules);
$rules = $registry->toArray();

$firewall = new Firewall($rules);
$firewall->authorize($user, $input);
