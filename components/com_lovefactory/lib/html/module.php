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

class JHTMLModuleConfiguration
{
    public static function configurationLink($moduleId)
    {
        $output = '<div class="lovefactory-module-configuration" style="display: none;">'
            . '</div>'
            . '<div class="lovefactory-module-configuration-link">'
            . '  <a href="#" rel="' . $moduleId . '">' . JText::_('MOD_LOVEFACTORY_CONFIGURE_LINK') . '</a>'
            . '</div>';

        return $output;
    }

    public static function genders($selected = array())
    {
        $genders = self::getGenders();
        $output = '';

        foreach ($genders as $id => $gender) {
            $checked = in_array($id, $selected) ? 'checked="checked"' : '';
            $output .= '<li><input type="checkbox" id="gender_' . $id . '" name="gender" value="' . $id . '" ' . $checked . ' /><label for="gender_' . $id . '">' . $gender . '</label></li>';
        }

        return $output;
    }

    protected static function getGenders()
    {
        static $genders = null;

        if (is_null($genders)) {
            $dbo = JFactory::getDbo();

            $query = $dbo->getQuery(true)
                ->select('f.values')
                ->from('#__lovefactory_fields f')
                ->where('f.type_id = 10');
            $dbo->setQuery($query);
            $genders = explode('*|*', $dbo->loadResult());
        }

        return $genders;
    }
}
