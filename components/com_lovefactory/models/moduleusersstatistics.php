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

class FrontendModelModuleUsersStatistics extends FrontendModelModule
{
    public function getItems()
    {
        $dbo = JFactory::getDbo();

        $table = JTable::getInstance('Field', 'Table');
        $table->load(array('type' => 'Gender'));
        $field = LoveFactoryField::getInstance($table->type, $table);

        $genders = $field->getChoices();

        // Get totals
        $query = $dbo->getQuery(true)
            ->select('COUNT(1) AS count, p.sex')
            ->from('#__lovefactory_profiles p')
//      ->where('p.validated = 1')
            ->where('p.sex IS NOT NULL')
            ->group('p.sex')
            ->order('count DESC');
        $dbo->setQuery($query);
        $total = $dbo->loadObjectList('sex');

        // Get grand totals
        $gtotal = 0;
        foreach ($total as $i => $values) {
            $gtotal += $values->count;

            $total[$i]->genderName = $genders[$i];
        }

        return (object)array('genders' => $total, 'total' => $gtotal);
    }
}
