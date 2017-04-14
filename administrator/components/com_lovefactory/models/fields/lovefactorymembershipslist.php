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

JFormHelper::loadFieldType('List');

class JFormFieldLoveFactoryMembershipsList extends JFormFieldList
{
    public $type = 'LoveFactoryMembershipsList';

    protected function getOptions()
    {
        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('m.title AS text, m.id AS value')
            ->from('#__lovefactory_memberships m')
            ->order('m.ordering ASC');

        $results = $dbo->setQuery($query)
            ->loadObjectList();

        array_unshift($results, array('value' => '', 'text' => ''));

        return $results;
    }
}
