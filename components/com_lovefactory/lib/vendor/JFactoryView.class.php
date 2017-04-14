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

jimport('joomla.application.component.view');

class JFactoryView extends JViewLegacy
{
    var $_enable_smarty;
    var $_smarty;
    var $_template_path;
    var $_debug = false;

    protected
        $get = array(),
        $tpl = null;

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function display($tpl = null)
    {
        foreach ($this->get as $get) {
            $this->{$get} = $this->get($get);
        }

        $this->loadAssets();

        if (is_null($tpl)) {
            $tpl = $this->tpl;
        }

        parent::display($tpl);
    }

    protected function loadAssets()
    {
        // Initialise variables.
        $name = $this->getName();
        $path = 'components/com_lovefactory/assets/';

        // Load stylesheets.
        JHTML::stylesheet($path . 'css/main2.css');
        JHtml::stylesheet($path . 'css/buttons2.css');
        JHtml::stylesheet($path . 'css/views/' . $name . '.css');

        JHtml::stylesheet($path . 'css/jquery.tipsy.css');

        // Load javascripts.
        JHtml::script($path . 'js/jquery.js');
        JHtml::script($path . 'js/jquery.tipsy.js');
        JHtml::script($path . 'js/jquery.tooltip.js');
        JHtml::script($path . 'js/jquery.noconflict.js');
        JHtml::script($path . 'js/views/' . $name . '.js');

        // Load html helper.
        JLoader::register('JHtmlLoveFactory', LoveFactoryApplication::getInstance()->getPath('component_site') . DS . 'lib' . DS . 'html' . DS . 'html.php');

        return true;
    }

    function __construct2($config = array())
    {
        parent::__construct($config);

        $this->getSmarty();
    }

    function getSmarty()
    {
        $settings = new LovefactorySettings();
        $this->_enable_smarty = $settings->enable_smarty;

        if ($this->_enable_smarty) {
            $base_path = JPATH_COMPONENT_SITE . DS . 'views';
            $view_path = $base_path . DS . $this->getName() . DS . 'smarty';

            require(JPATH_COMPONENT_SITE . DS . 'lib' . DS . 'Smarty' . DS . 'Smarty.class.php');
            $this->_smarty = new Smarty();

            $this->_smarty->template_dir = JPATH_COMPONENT_SITE . DS . 'lib' . DS . 'Smarty' . DS . 'user' . DS . 'templates';
            $this->_smarty->compile_dir = JPATH_COMPONENT_SITE . DS . 'lib' . DS . 'Smarty' . DS . 'user' . DS . 'templates_c';
            $this->_smarty->cache_dir = JPATH_COMPONENT_SITE . DS . 'lib' . DS . 'Smarty' . DS . 'user' . DS . 'cache';
            $this->_smarty->config_dir = JPATH_COMPONENT_SITE . DS . 'lib' . DS . 'Smarty' . DS . 'user' . DS . 'configs';

            $this->_smarty->_base_path = $base_path;
            $this->_smarty->_view_path = $view_path;
        }
    }

    // Display
    function display2($tpl = null)
    {
        $this->loadAssets();

        if ($this->_enable_smarty) {
            echo $this->loadTemplate($tpl);
        } else {
            parent::display($tpl);
        }
    }

    function loadTemplate($tpl = null)
    {
        // If Smarty templates are enabled
        if ($this->_enable_smarty) {
            // Create template filepath
            $template = $this->_smarty->_view_path . DS . $this->getLayout() . (!is_null($tpl) ? '_' . $tpl : '') . '.tpl';

            // Check if template exists
            if (file_exists($template)) {
                $this->_smarty->fetch($template);
            } else {
                throw new Exception(JText::sprintf('SMARTY_ERROR_TEMPLATE_NOT_FOUND', $this->getLayout()), 500);
            }

            $debug = $this->_debug ? '<span style="font-weight: bold; color: #ff0000;">Smarty Template!</span>' : '';

            return $debug . $this->_smarty->fetch($template);
        }

        // Smarty templates are not enabled
        return parent::loadTemplate($tpl);
    }

    function debugSWFUpload()
    {
        $settings = new LovefactorySettings();

        return $settings->enable_swfupload_debug ? true : false;
    }

    function photosMaxSize()
    {
        $settings = new LovefactorySettings();

        return $settings->photos_max_size . 'MB';
    }

    // Assign
    function assignRef($key, &$val)
    {
        if ($this->_enable_smarty) {
            $this->_smarty->assign($key, $val);
        } else {
            $this->$key = $val;
        }
    }

    function assign()
    {
        $arg0 = @func_get_arg(0);
        $arg1 = @func_get_arg(1);

        if ($this->_enable_smarty) {
            $this->_smarty->assign($arg0, $arg1);
        } else {
            $this->$arg0 = $arg1;
        }
    }

    function assignJavascript($key, $value)
    {
        if (!isset($this->document)) {
            $this->document = JFactory::getDocument();
        }

        if (is_array($value)) {
            foreach ($value as $i => $val) {
                $value[$i] = addslashes($val);
            }

            $value = 'new Array("' . implode('", "', $value) . '")';
            $this->document->addScriptDeclaration('var ' . $key . ' = ' . $value . ';');
        } elseif (is_bool($value)) {
            $this->document->addScriptDeclaration('var ' . $key . ' = ' . ($value ? 'true' : 'false') . ';');
        } else {
            $this->document->addScriptDeclaration('var ' . $key . ' = "' . addslashes($value) . '";');
        }
    }
}
