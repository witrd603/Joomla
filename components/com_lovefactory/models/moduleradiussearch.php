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

class FrontendModelModuleRadiusSearch extends FrontendModelModule
{
    public function getMap()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        $map = LoveFactoryGoogleMaps::getInstance($settings->gmaps_api_key);

        return $map;
    }

    public function getPage($page = 'radius_search', $mode = 'search')
    {
        $page = LoveFactoryPage::getInstance($page, $mode);

        $settings = LoveFactoryApplication::getInstance()->getSettings();
        if ($settings->opposite_gender_search) {
            $page->removeGenderFields();
        }

        return $page;
    }

    public function getLocation()
    {
        $model = JModelLegacy::getInstance('MembersMap', 'FrontendModel');

        return $model->getLocation();
    }
}
