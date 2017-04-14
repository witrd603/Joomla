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

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldMemberships extends JFormField
{
    protected function getInput()
    {
        $output = JHTML::_(
            'select.genericlist',
            $this->getOptions(),
            $this->name . '[]',
            'class="inputbox" size="8" multiple="multiple"',
            'id',
            'title',
            $this->value
        );

        return $output;
    }

    private function getOptions()
    {
        $dbo = JFactory::getDBO();

        $query = $dbo->getQuery(true)
            ->select('m.id, m.title')
            ->from('#__lovefactory_memberships m')
            ->where('m.published = 1')
            ->order('m.ordering ASC');
        $dbo->setQuery($query);

        return $dbo->loadObjectList();
    }
}
