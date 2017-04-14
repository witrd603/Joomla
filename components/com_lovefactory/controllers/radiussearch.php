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

class FrontendControllerRadiusSearch extends FrontendController
{
    public function search()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $model = $this->getModel('Radiussearch');
        $request = JFactory::getApplication()->input->get('form', array(), 'array');
        $results = $model->getResults($request);
        $data = $model->getState('data.filtered.radius');
        $distance = $data['radius']['distance'] * ($settings->distances_unit ? 1.6 : 1);

        if (!$data['radius']['distance']) {
            $results = array();
        } else {
            $results = array('results' => $results, 'distance' => $distance);
        }

        $this->renderJson($results);

        return true;
    }
}
