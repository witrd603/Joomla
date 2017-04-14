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

jimport('joomla.filesystem.file');

class LoveFactoryPageRenderer
{
    protected $page;
    protected $templates = array();
    protected $templatesPath = 'templates';

    public function __construct($templates = array())
    {
        $this->templates = $templates;
    }

    public function setTemplate($name, $template)
    {
        $this->templates[$name] = $template;

        return $this;
    }

    public function getTemplate($name)
    {
        if (isset($this->templates[$name])) {
            return $this->templates[$name];
        }

        return $name;
    }

    public static function getInstance($templates = array())
    {
        return new self($templates);
    }

    public function render($page, $data = null)
    {
        $this->page = $page;

        if (!is_null($data)) {
            $page->bind($data);
        }

        return $this->loadTemplate('page');
    }

    protected function loadTemplate($template)
    {
        $template = $this->getTemplate($template);
        $contents = '';

        if (false === strpos($template, '/')) {
            $path = dirname(__FILE__) . DS . $this->templatesPath . DS . $template . '.php';

            if (!JFile::exists($path)) {
                return $contents;
            }

            ob_start();
            include $path;
            $contents = ob_get_contents();
            ob_end_clean();
        } else {
            list ($view, $tpl) = explode('/', $template);

            $file = LoveFactoryApplication::getInstance()->getPath('component_site') . DS . 'views' . DS . $view . DS . 'tmpl' . DS . $tpl . '.tpl';
            if (!JFile::exists($file)) {
                return $contents;
            }

            $data = $this->page->getData();
            $settings = LoveFactoryApplication::getInstance()->getSettings();

            $smarty = LoveFactoryApplication::getInstance()->getSmarty();
            $smarty->assignByRef('profile', $data);
            $smarty->assignByRef('settings', $settings);

            ob_start();
            $smarty->display($file);
            $contents = ob_get_contents();
            ob_end_clean();
        }

        return $contents;
    }
}
