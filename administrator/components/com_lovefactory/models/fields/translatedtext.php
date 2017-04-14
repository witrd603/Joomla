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

class JFormFieldTranslatedText extends JFormField
{
    protected $type = 'TranslatedText';

    protected function getInput()
    {
        $languages = JLanguageHelper::getLanguages();
        array_unshift($languages, (object)array('title' => JText::_('JDEFAULT'), 'lang_code' => 'default'));

        $html = array();

        $html[] = '<div class="' . $this->class . '">';

        foreach ($languages as $language) {
            $id = $this->id . '_' . $language->lang_code;
            $name = $this->name . '[' . $language->lang_code . ']';
            $value = isset($this->value[$language->lang_code])
                ? $this->value[$language->lang_code]
                : '';

            $html[] = '<label for="' . $id . '">' . $language->title . '</label>';
            $html[] = '<input id="' . $id . '" name="' . $name . '" value="' . htmlentities($value) . '" />';
        }

        $html[] = '</div>';

        return implode("\n", $html);
    }
}
