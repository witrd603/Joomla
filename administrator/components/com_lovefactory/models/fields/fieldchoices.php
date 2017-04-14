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

class JFormFieldFieldChoices extends JFormField
{
    protected $type = 'FieldChoices';

    protected function getInput()
    {
        $languages = JLanguageHelper::getLanguages();
        array_unshift($languages, (object)array('title' => JText::_('JDEFAULT'), 'lang_code' => 'default'));

        $html = array();

        $html[] = '<div>';

        foreach ($languages as $language) {
            $id = $this->id . '_' . $language->lang_code;
            $name = $this->name . '[' . $language->lang_code . ']';
            $value = isset($this->value[$language->lang_code]) ? implode(PHP_EOL, $this->value[$language->lang_code]) : '';

            $html[] = '<label for="' . $id . '">' . $language->title . '</label>';
            $html[] = '<textarea id="' . $id . '" name="' . $name . '" cols="20" rows="10">' . $value . '</textarea>';
        }

        $html[] = '</div>';

        return implode("\n", $html);
    }

    public function filter($data)
    {
        $data = (array)$data;

        foreach ($data as $lang => $values) {
            $values = trim($values);

            if ('' == $values) {
                unset($data[$lang]);
                continue;
            }

            //$data[$lang] = explode(PHP_EOL, $values);
            $data[$lang] = preg_split('/\r\n|\r|\n/', $values);
        }

        return $data;
    }
}
