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

class FrontendModelFixedSearch extends FactoryModel
{
    protected $page = null;

    public function getRequest()
    {
        $request = JFactory::getApplication()->input->get('form', array(), 'array');

        if (!$request) {
            $request = JFactory::getApplication()->input->get('module_search', array(), 'array');
        }

        return $request;
    }

    public function getViewResults()
    {
        $request = $this->getRequest();

        if (!$request) {
            return '';
        }

        $fields = array();

        $plugin = JPluginHelper::getPlugin('system', 'lovefactoryrouter');
        $params = new \Joomla\Registry\Registry($plugin->params);

        $segments = $params->get('segments', array());

        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('f.*')
            ->from($dbo->qn('#__lovefactory_fields', 'f'))
            ->where('f.id IN (' . implode(',', $dbo->q($segments)) . ')');
        $fields = $dbo->setQuery($query)
            ->loadObjectList();

        $array = array();
        foreach ($fields as $field) {
            $array[] = LoveFactoryField::getInstance($field->type, $field);
        }

        JLoader::register('FrontendViewResults', LoveFactoryApplication::getInstance()->getPath('component_site') . DS . 'views' . DS . 'results' . DS . 'view.html.php');
        $model = JModelLegacy::getInstance('Results', 'FrontendModel', array(
            'fields' => $array,
            'request' => $request,
        ));
        $view = new FrontendViewResults();

        $view->setModel($model, true);

        ob_start();
        $view->display();
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }
}
