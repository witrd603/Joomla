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

JLoader::register('FrontendModelSearch', LoveFactoryApplication::getInstance()->getPath('component_site') . DS . 'models' . DS . 'search.php');

class FrontendModelOnline extends FrontendModelSearch
{
    public function getViewResults()
    {
        JLoader::register('FrontendViewResults', LoveFactoryApplication::getInstance()->getPath('component_site') . DS . 'views' . DS . 'results' . DS . 'view.html.php');
        $model = JModelLegacy::getInstance('Results', 'FrontendModel', array(
            'fields' => $this->getPage()->getFields(),
            'online' => true,
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
