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

JLoader::register('FrontendController', JPATH_SITE . '/components/com_lovefactory/controller.php');

class FrontendControllerModule extends FrontendController
{
    protected $basePath;

    public function __construct($config = array())
    {
        require_once JPATH_SITE . '/components/com_lovefactory/vendor/autoload.php';

        parent::__construct($config);

        require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'lib' . DS . 'application.php';
        require_once(JPATH_SITE . DS . 'components' . DS . 'com_lovefactory' . DS . 'lib' . DS . 'html' . DS . 'html.php');
        require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'lib' . DS . 'methods.php';

        $this->basePath = JPATH_SITE . DS . 'components' . DS . 'com_lovefactory';
        JLoader::register('LoveFactorySettings', JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_lovefactory' . DS . 'settings.php');
        JLoader::register('FactoryText', JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_lovefactory' . DS . 'lib' . DS . 'methods.php');
        JLoader::register('FrontendModelModule', JPATH_SITE . DS . 'components' . DS . 'com_lovefactory' . DS . 'models' . DS . 'module.php');

        parent::addViewPath($this->basePath . DS . 'views');
        parent::addModelPath($this->basePath . DS . 'models');

        // Include language
        $language = JFactory::getLanguage();
        $language->load('com_lovefactory');

        LoveFactoryApplication::getInstance();
    }

    public function render($module, $params)
    {
        // Get request
        $format = JFactory::getApplication()->input->getCmd('format', 'html');
        $name = $this->getModuleName($module->module);

        // Get model and view
        $model = $this->getModel($name);
        $view = $this->getView($name, $format);

        // Initialise model
        $model->setModule($module);
        $model->setParams($params);

        // Initialise view
        $view->addTemplatePath($this->basePath . DS . 'views' . DS . $name . DS . 'tmpl');
        $view->addTemplatePath(JPATH_THEMES . '/' . JFactory::getApplication()->getTemplate() . '/html/com_lovefactory/' . $name);
        $view->setModel($model, true);

        // Load assets
        require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'lib' . DS . 'methods.php';
        FactoryHtml::stylesheet('main');
        FactoryHtml::stylesheet('icons');
        FactoryHtml::stylesheet('modules');
        FactoryHtml::script('modules');

        // Display
        $view->display();
    }

    public function pagination()
    {
        // Get request
        $id = JFactory::getApplication()->input->getInt('id', 0);

        // Get model
        $model = $this->getModel('Module', 'FrontendModel');
        $model->addTablePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'tables');

        // Check if module is valid
        if (!$model->load($id)) {
            echo $model->getError();
            return false;
        }

        $temp = $model->getModule();

        $name = $this->getModuleName($temp->module);
        $params = new JRegistry($temp->params);

        $model = $this->getModel($name);
        $model->setModule($temp);
        $model->setParams($params);

        // Initialise view
        $view = $this->getView($name, 'raw', 'FrontendView');
        $view->addTemplatePath($this->basePath . DS . 'views' . DS . $name . DS . 'tmpl');
        $view->addTemplatePath(JPATH_THEMES . '/' . JFactory::getApplication()->getTemplate() . '/html/com_lovefactory/' . $name);
        $view->setModel($model, true);

        // Display
        $view->display();

        if ($this->isAjaxRequest()) {
            jexit();
        }
    }

    public function reload()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $model = $this->getModel('Module', 'FrontendModel');

        // Check if module is valid
        if (!$model->load($id)) {
            echo $model->getError();
            return false;
        }

        $this->displayModule($model->getModule(), $model->getParams());
    }

    public function config()
    {
        // Get request
        $id = JFactory::getApplication()->input->getInt('id', 0);

        // Get model
        $model = $this->getModel('ModuleMembers', 'FrontendModel');
        $model->addTablePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'tables');

        // Check if module is valid
        if (!$model->load($id)) {
            echo $model->getError();
            return false;
        }

        // Initialise view
        $view = $this->getView('ModConfig', 'raw', 'FrontendView');
        $view->addTemplatePath($this->basePath . DS . 'views' . DS . 'modconfig' . DS . 'tmpl');
        $view->addTemplatePath(JPATH_THEMES . '/' . JFactory::getApplication()->getTemplate() . '/html/com_lovefactory/modconfig/');
        $view->setModel($model, true);

        // Display
        $view->display();
    }

    public function shoutboxGetMessages()
    {
        $model = $this->getModel('ModuleShoutbox');
        $lastUpdate = JFactory::getApplication()->input->getCmd('lastupdate', '');
        $messages = $model->getMessages($lastUpdate);

        $response = array(
            'lastUpdate' => $model->getState('lastUpdate'),
            'messages' => $messages,
        );

        echo json_encode($response);

        jexit();
    }

    public function shoutboxPostMessage()
    {
        $model = $this->getModel('ModuleShoutbox');
        $message = JFactory::getApplication()->input->getString('message', '');
        $model->postMessage($message);

        jexit();
    }

    public function getModel($name = '', $prefix = 'FrontendModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function getView($name = '', $format = 'html', $prefix = 'FrontendView', $config = array())
    {
        return parent::getView($name, $format, $prefix, $config);
    }

    public function displayModule($module, $params)
    {
        // Get request
        $format = JFactory::getApplication()->input->getCmd('format', 'html');

        // Get model and view
        $model = $this->getModel('ModuleMembers', 'FrontendModel');
        $view = $this->getView('ModuleMembers', $format, 'FrontendView');

        // Initialise model
        $model->setModule($module);
        $model->setParams($params);

        // Initialise view
        $view->setModel($model, true);

        // Display
        $view->display();
    }

    protected function getModuleName($name)
    {
        return str_replace(array('mod_lovefactory_', '_'), array('module', ''), $name);
    }
}
