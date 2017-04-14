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

function getRoutes()
{
    $routes = array(
        'activity-stream' => array('view' => 'activity', 'params' => array('optional' => 'user_id')),
        'advanced-search' => array('view' => 'advanced'),
        'approvals' => array('view' => 'approvals'),
        'blocked-list' => array('view' => 'blocked'),
        'profile-comments' => array('view' => 'comments', 'params' => array('optional' => 'id')),
        'compose-message' => array('view' => 'compose', 'params' => array('optional' => 'receiver')),
        'update-profile' => array('view' => 'edit'),
        'fillin-profile' => array('view' => 'fillin'),
        'friends' => array('view' => 'friends'),
        'group' => array('view' => 'group', 'params' => array('id')),
        'group-banned-users' => array('view' => 'groupbanned', 'params' => array('id')),
        'group-update' => array('view' => 'groupedit', 'params' => array('id')),
        'group-add' => array('view' => 'groupedit'),
        'group-members' => array('view' => 'groupmembers', 'params' => array('id')),
        'groups' => array('view' => 'groups'),
        'group-thread' => array('view' => 'groupthread', 'params' => array('id')),
        'group-thread-add' => array('view' => 'groupthreadedit', 'params' => array('id')),
        'group-threads' => array('view' => 'groupthreads', 'params' => array('id')),
        'inbox' => array('view' => 'inbox'),
        'interactions-list' => array('view' => 'interactions'),
        'invoice' => array('view' => 'invoice', 'params' => array('id', 'tmpl')),
        'invoices' => array('view' => 'invoices'),
        'purchase-membership' => array('view' => 'membershipbuy', 'params' => array('id')),
        'memberships' => array('view' => 'memberships'),
        'members-map' => array('view' => 'membersmap'),
        'message' => array('view' => 'message', 'params' => array('id')),
        'my-friends' => array('view' => 'myfriends'),
        'my-membership' => array('view' => 'mymembership'),
        'my-memberships' => array('view' => 'mymemberships'),
        'my-relationship' => array('view' => 'myrelationship'),
        'online-users' => array('view' => 'online'),
        'outbox' => array('view' => 'outbox'),
        'payment' => array('view' => 'payment'),
        'photo' => array('view' => 'photo', 'params' => array('id')),
        'photos' => array('view' => 'photos'),
        'profile' => array('view' => 'profile', 'params' => array('optional' => 'user_id')),
        'radius-search' => array('view' => 'radiussearch'),
        'requests-list' => array('view' => 'requests'),
        'search' => array('view' => 'search'),
        'settings' => array('view' => 'settings'),
        'registration' => array('view' => 'signup'),
        'top-friends' => array('view' => 'topfriends'),
        'video' => array('view' => 'video', 'params' => array('id')),
        'videos' => array('view' => 'videos'),

        // Comments.
        'update-comments' => array('view' => 'itemcomments'),
        'submit-comment' => array('controller' => 'itemcomment', 'task' => 'add'),
        'delete-comment' => array('controller' => 'itemcomment', 'task' => 'delete'),

        // Activity stream.
        'delete-activity-stream-entry' => array('controller' => 'activity', 'task' => 'delete'),

        // Ajax dialog.
        'dialog' => array('view' => 'dialog'),

        // Friends.
        'remove-friend' => array('controller' => 'friend', 'task' => 'remove', 'params' => array('id')),
        'promote-friend' => array('controller' => 'friend', 'task' => 'promote', 'params' => array('user_id')),

        // Messages.
        'send-message' => array('controller' => 'message', 'task' => 'send'),

        // Report.
        'send-report' => array('controller' => 'report', 'task' => 'send'),

        'confirm-purchase' => array('controller' => 'gateway', 'task' => 'process'),
    );

    return $routes;
}

function lovefactoryBuildRoute(&$query)
{
    if (!isset($query['view']) && !isset($query['controller'])) {
        return array();
    }

    $routes = getRoutes();
    $segments = array();

    foreach ($routes as $alias => $route) {
        if (isset($query['view'])) {
            if (!isset($route['view']) || $route['view'] != $query['view']) {
                continue;
            }
        } elseif (isset($query['controller'])) {
            if (!isset($route['controller']) || $route['controller'] != $query['controller'] || $route['task'] != $query['task']) {
                continue;
            }
        }

        $valid = true;
        $temp = array();

        if (isset($route['params'])) {
            foreach ($route['params'] as $type => $param) {
                if (!isset($query[$param])) {
                    if ('optional' !== $type) {
                        $valid = false;
                        break;
                    }
                }
            }
        }

        if (!$valid) {
            continue;
        }

        $segments[] = $alias;
        if (isset($query['view'])) {
            unset($query['view']);
        } else {
            unset($query['controller']);
        }

        if (isset($route['controller'])) {
            unset($query['task']);
        }

        if (isset($route['params'])) {
            foreach ($route['params'] as $param) {
                if (isset($query[$param])) {
                    $segments[] = $query[$param];
                    unset($query[$param]);
                }
            }
        }

        break;
    }

    return $segments;
}

function lovefactoryParseRoute($segments)
{
    $routes = getRoutes();
    $vars = array();

    $segments[0] = str_replace(':', '-', $segments[0]);

    if (array_key_exists($segments[0], $routes)) {
        $route = $routes[$segments[0]];

        if (isset($route['view'])) {
            $vars['view'] = $route['view'];
        } else {
            $vars['controller'] = $route['controller'];
            $vars['task'] = $route['task'];
        }

        if (isset($route['params'])) {
            $i = 1;
            foreach ($route['params'] as $type => $param) {
                if (isset($segments[$i])) {
                    $vars[$param] = $segments[$i];
                }

                $i++;
            }
        }
    }

    return $vars;
}
