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

class JHtmlTranslation
{
    public function input($item, $field, $values = false)
    {
        return self::template('input', $item, $field, $values);
    }

    public function textarea($item, $field, $values = false)
    {
        return self::template('textarea', $item, $field, $values);
    }

    public function editor($item, $field, $values = false, $editor)
    {
        return self::template('editor', $item, $field, $values, $editor);
    }

    public function pageTitle($page, $id)
    {
        $enabled = self::getEnabled();

        if (!$enabled) {
            return '';
        }

        $languages = self::getLanguages();
        $output = self::templateTop();

        foreach ($languages as $language) {
            $value = isset($page->translations[$language->lang_code]->titles[$id]) ? $page->translations[$language->lang_code]->titles[$id] : '';

            $output .= '<li style="clear: both;">'
                . '  <label for="translation_' . $language->lang_code . '_title" style="margin-right: 5px;">' . $language->title . ':</label>'
                . '  <input type="text" id="translation_' . $language->lang_code . '_title" name="translation[' . $language->lang_code . '][title][]" value="' . $value . '" />'
                . '</li>';
        }

        $output .= self::templateBottom();

        return $output;
    }

    public static function membershipTitle($page)
    {
        $enabled = self::getEnabled();

        if (!$enabled) {
            return '';
        }

        $languages = self::getLanguages();
        $output = self::templateTop();

        foreach ($languages as $language) {
            $value = isset($page->translations[$language->lang_code]->title) ? $page->translations[$language->lang_code]->title : '';

            $output .= '<li style="clear: both;">'
                . '  <label for="translation_' . $language->lang_code . '_title" style="margin-right: 5px;">' . $language->title . ':</label>'
                . '  <input type="text" id="translation_' . $language->lang_code . '_title" name="translation[' . $language->lang_code . '][title]" value="' . $value . '" />'
                . '</li>';
        }

        $output .= self::templateBottom();

        return $output;
    }

    protected function template($type, $item, $field, $values = false, $editor = null)
    {
        $enabled = self::getEnabled();

        if (!$enabled) {
            return '';
        }

        $languages = self::getLanguages();
        $output = self::templateTop();

        foreach ($languages as $language) {
            $value = isset($item->translations[$language->lang_code]->$field) ? $item->translations[$language->lang_code]->$field : '';

            $output .= '<li style="clear: both;">'
                . '  <label for="translation_' . $language->lang_code . '_' . $field . '" style="margin-right: 5px;">' . $language->title . ':</label>'
                . self::element($type, $language, $field, $value, $values, $editor)
                . '</li>';
        }

        $output .= self::templateBottom();

        return $output;
    }

    protected function templateTop()
    {
        $output = '<fieldset style="clear: both;">'
            . '  <legend>' . JText::_('TRANSLATION') . '</legend>'
            . '    <ul>';

        return $output;
    }

    protected function templateBottom()
    {
        $output = '  </ul>'
            . '</fieldset>';

        return $output;
    }

    protected function element($type, $language, $field, $value, $values = false, $editor = null)
    {
        if ($values) {
            $value = implode("\n", explode('*|*', $value));
        }

        $output = '';

        switch ($type) {
            case 'input':
                $output = '<input type="text" id="translation_' . $language->lang_code . '_' . $field . '" name="translation[' . $language->lang_code . '][' . $field . ']" value="' . $value . '" />';
                break;

            case 'textarea':
                $output = '<textarea id="translation_' . $language->lang_code . '_' . $field . '" name="translation[' . $language->lang_code . '][' . $field . ']">' . $value . '</textarea>';
                break;

            case 'editor':
                $output = $editor->display('translation[' . $language->lang_code . '][' . $field . ']', $value, '800', '550', '75', '20');
                break;
        }

        return $output;
    }

    protected function getLanguages()
    {
        static $languages = null;

        if (is_null($languages)) {
            jimport('joomla.language.helper');
            $languages = JLanguageHelper::getLanguages();
        }

        return $languages;
    }

    protected static function getEnabled()
    {
        static $enabled = null;

        if (is_null($enabled)) {
            $settings = new LovefactorySettings();
            $enabled = $settings->show_translation_fields;
        }

        return $enabled;
    }
}
