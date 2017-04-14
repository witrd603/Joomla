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

jimport('joomla.application.component.model');

class FrontendModelModule extends FactoryModel
{
    protected $module;
    protected $params;

    public function __construct($config = array())
    {
        parent::__construct($config);

        JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'tables');
    }

    public function load($id)
    {
        $module = JTable::getInstance('Module');
        $module->load($id);

        // Is this a Love Factory module
        if (0 !== strpos($module->module, 'mod_lovefactory_', 0)) {
            $this->setError(JText::_('MOD_LOVEFACTORY_MODULE_NOT_FOUND'));
            return false;
        }

        $this->setModule($module);

        $params = new JRegistry($module->params);
        $this->setParams($params);

        return true;
    }

    public function setModule($module)
    {
        $this->module = $module;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getModuleId()
    {
        return $this->module->id;
    }

    public function getModuleClass()
    {
        return $this->params->get('moduleclass_sfx');
    }

    public function getModuleName()
    {
        return $this->module->module;
    }
}
