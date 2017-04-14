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

class JHtmlField
{
    public function article($id, $default = null)
    {
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->select('c.id AS value, c.title AS text')
            ->from('#__content c')
            ->order('c.title ASC');
        $dbo->setQuery($query);

        $articles = $dbo->loadObjectList();

        array_unshift($articles, array('value' => 0, 'text' => JText::_('SELECT_ARTICLE')));

        return JHtml::_('select.genericlist', $articles, $id, '', 'value', 'text', $default);
    }
}
