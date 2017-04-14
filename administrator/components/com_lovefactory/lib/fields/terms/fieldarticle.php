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

class JFormFieldFieldArticle extends JFormField
{
    public $type = 'FieldArticle';

    protected function getInput()
    {
        // Initialise variables.
        $html = array();
        $languages = JLanguageHelper::getLanguages();
        $articles = $this->getArticles();

        array_unshift($languages, (object)array('title' => JText::_('JDEFAULT'), 'lang_code' => 'default'));

        foreach ($languages as $language) {
            $id = $this->id . '_' . $language->lang_code;
            $name = $this->name . '[' . $language->lang_code . ']';
            $value = isset($this->value[$language->lang_code]) ? $this->value[$language->lang_code] : '';

            $html[] = '<label for="' . $id . '">' . $language->title . '</label>';
            $html[] = JHtml::_('select.genericlist', $articles, $name, '', 'value', 'text', $value, $id);
        }

        return implode("\n", $html);
    }

    protected function getArticles()
    {
        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('c.title AS text, c.id AS value')
            ->from('#__content c')
            ->order('c.title ASC');

        $articles = $dbo->setQuery($query)
            ->loadObjectList();

        array_unshift($articles, array('value' => '', 'text' => ''));

        return $articles;
    }
}
