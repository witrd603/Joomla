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

class JFormFieldBirthdateField extends JFormFieldList
{
    protected function getOptions()
    {
        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('f.title AS text, CONCAT("field_", f.id) AS value')
            ->from($dbo->qn('#__lovefactory_fields', 'f'))
            ->where('f.type = ' . $dbo->q('Birthdate'));

        $results = $dbo->setQuery($query)
            ->loadObjectList();

        return $results;
    }
}
