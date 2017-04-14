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

jimport('joomla.application.component.controller');

class BackendController extends JControllerLegacy
{
    function __construct($config = array())
    {
        parent::__construct($config);

        require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'settings.php');
        require_once(JPATH_COMPONENT_SITE . DS . 'lib' . DS . 'vendor' . DS . 'html.php');

        JLoader::register('LoveFactoryHelper', JPATH_COMPONENT_ADMINISTRATOR . DS . 'lib' . DS . 'helper.php');
    }

    public function display($cachable = false, $urlparams = array())
    {
        $this->addSubmenu();

        parent::display();
    }

    function dashboard()
    {
        $this->addSubmenu();

        $view = $this->getView('dashboard', 'html');
        $model = $this->getModel('dashboard');

        $view->setModel($model, true);
        $view->display();
    }

    function fields()
    {
        $this->addSubmenu();

        $view = $this->getView('fields', 'html');
        $model = $this->getModel('fields');

        $view->setModel($model, true);
        $view->display();
    }

    function pages()
    {
        $this->addSubmenu();

        $view = $this->getView('pages', 'html');
        $model = $this->getModel('pages');

        $view->setModel($model, true);
        $view->display();
    }

    function settings()
    {
        $this->addSubmenu();

        $view = $this->getView('settings', 'html');
        $model = $this->getModel('settings');

        $view->setModel($model, true);
        $view->display();
    }

    public function configuration()
    {
        $this->addSubmenu();

        $view = $this->getView('configuration', 'html');
        $model = $this->getModel('configuration');

        $view->setModel($model, true);
        $view->display();
    }

    function users()
    {
        $this->addSubmenu();

        $view = $this->getView('users', 'html');
        $model = $this->getModel('users');

        $view->setModel($model, true);
        $view->display();
    }

    function reports()
    {
        $this->addSubmenu();

        $view = $this->getView('reports', 'html');
        $model = $this->getModel('reports');

        $view->setModel($model, true);
        $view->display();
    }

    function about()
    {
        $this->addSubmenu();

        $view = $this->getView('about', 'html');
        $model = $this->getModel('about');

        $view->setModel($model, true);
        $view->display();
    }

    function pricing()
    {
        $this->addSubmenu();

        $view = $this->getView('pricing', 'html');
        $model = $this->getModel('pricing');

        $view->setModel($model, true);
        $view->display();
    }

    function groups()
    {
        $this->addSubmenu();

        $view = $this->getView('groups', 'html');
        $model = $this->getModel('groups');

        $view->setModel($model, true);
        $view->display();
    }

    function approvals()
    {
        $this->addSubmenu();

        $view = $this->getView('approvals', 'html');
        $model = $this->getModel('approvals');

        $view->setModel($model, true);
        $view->display();
    }

    function groupMembers()
    {
        $this->addSubmenu();

        $view = $this->getView('GroupMembers', 'html');
        $model = $this->getModel('GroupMembers');

        $view->setModel($model, true);
        $view->display();
    }

    function notifications()
    {
        $this->addSubmenu();

        $view = $this->getView('notifications', 'html');
        $model = $this->getModel('notifications');

        $view->setModel($model, true);
        $view->display();
    }

    function payments()
    {
        $this->addSubmenu();

        $view = $this->getView('payments', 'html');
        $model = $this->getModel('payments');

        $view->setModel($model, true);
        $view->display();
    }

    function orders()
    {
        $this->addSubmenu();

        $view = $this->getView('orders', 'html');
        $model = $this->getModel('orders');

        $view->setModel($model, true);
        $view->display();
    }

    function invoices()
    {
        $this->addSubmenu();

        $view = $this->getView('invoices', 'html');
        $model = $this->getModel('invoices');

        $view->setModel($model, true);
        $view->display();
    }

    function backup()
    {
        $model = $this->getModel('backup');

        if (!$model->create()) {
            $msg = JText::_('Backup Error!');
        }

        $this->setRedirect('index.php?option=com_lovefactory&task=settings', $msg);
    }

    function restore()
    {
        $model = $this->getModel('backup');

        if ($model->restore()) {
            $msg = JText::_('Backup Restored Successfully!');
        } else {
            $msg = JText::_('Error Restoring Backup!');
            throw new Exception($model->getError());
        }

        $this->setRedirect('index.php?option=com_lovefactory&view=settings&layout=backup', $msg);
    }

    function memberships()
    {
        $this->addSubmenu();

        $view = $this->getView('memberships', 'html');
        $model = $this->getModel('memberships');

        // Check for default sold membership
        $model->checkDefaultSoldMembership();

        // Show the view
        $view->setModel($model, true);
        $view->display();
    }

    function gateways()
    {
        $this->addSubmenu();

        $view = $this->getView('gateways', 'html');
        $model = $this->getModel('gateways');

        $view->setModel($model, true);
        $view->display();
    }

    function getParameters()
    {
        $view = $this->getView('field', 'raw');
        $view->display();
    }

    function verifyFields()
    {
        $fields = JModelLegacy::getInstance('fields', 'BackendModel');
        $fields->verify();
    }

    function membershipsOptions()
    {
        $view = $this->getView('memberships', 'html');
        $model = $this->getModel('memberships');

        $view->setLayout('options');

        // Show the view
        $view->setModel($model, true);
        $view->display();
    }

    function usersOptions()
    {
        $view = $this->getView('users', 'html');
        $model = $this->getModel('users');

        $view->setLayout('options');

        // Show the view
        $view->setModel($model, true);
        $view->display();
    }

    function pricingOptions()
    {
        $view = $this->getView('pricing', 'html');
        $model = $this->getModel('pricing');

        $view->setLayout('options');

        // Show the view
        $view->setModel($model, true);
        $view->display();
    }

    public function downloadShoutBoxLog()
    {
        $url = JURI::root() . 'administrator/components/com_lovefactory/shoutbox_log.txt';

        header("Content-Type: text/plain");
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"shoutbox_log.txt\"");

        readfile($url);

        jexit();
    }

    private function addSubmenu()
    {
        return false;
    }
}
