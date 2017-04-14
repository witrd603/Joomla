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

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('List');

class JFormFieldFieldList extends JFormFieldList
{
    protected $type = 'FieldList';

    protected function getOptions()
    {
        $options = parent::getOptions();

        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('f.id AS value, f.title AS text')
            ->from('#__lovefactory_fields f')
            ->order('f.title ASC');

        if ('' != $filters = $this->element['filters']) {
            $filters = explode(',', $filters);
            foreach ($filters as &$filter) {
                $filter = $dbo->quote(trim($filter));
            }

            $query->where('f.type IN (' . implode(', ', $filters) . ')');
        }

        if ($id = $this->form->getValue('id')) {
            $query->where('f.id <> ' . $dbo->quote($id));
        }

        $results = $dbo->setQuery($query)
            ->loadObjectList();

        return array_merge($options, $results);
    }
}
