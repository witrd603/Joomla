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

class LoveFactoryFieldTerms extends LoveFactoryField
{
    protected $accessPageWhiteList = array('registration');
    protected $choices;

    public function __construct($type, $field, $mode)
    {
        parent::__construct($type, $field, $mode);

        $this->params->set('choices', array(1 => FactoryText::_('field_terms_checkbox_label')));
    }

    public function renderInputEdit()
    {
        $this->data = null;
        $route = $this->getArticleRoute();
        $html = array();

        $html[] = '<div style="margin-bottom: 10px;"><a target="_blank" href="' . $route . '"><i class="factory-icon icon-arrow-000-medium"></i>' . FactoryText::_('field_terms_click_to_read_label') . '</a></div>';
        $html[] = LoveFactoryFieldMultipleChoiceInterface::renderEditCheckbox($this->params->get('choices'), $this->getHtmlName(), $this->data, $this->getHtmlId(), array(1));

        return implode("\n", $html);
    }

    public function validate()
    {
        if (!parent::validate()) {
            return false;
        }

        $valid = LoveFactoryFieldMultipleChoiceInterface::validate($this->getLabel(), $this->data, $this->params->get('min_choices', 0), $this->params->get('max_choices', 0));

        if (true !== $valid) {
            $this->setError($valid);
            return false;
        }

        return true;
    }

    public function filterData()
    {
        $this->data = LoveFactoryFieldMultipleChoiceInterface::filterData($this->data, $this->params->get('choices', array()));
    }

    protected function getArticleRoute()
    {
        $lang = JFactory::getLanguage()->getTag();
        $default = $this->params->get('article.default', null);
        $articleId = $this->params->get('article.' . $lang, $default);

        JLoader::register('ContentHelperRoute', JPATH_SITE . DS . 'components' . DS . 'com_content' . DS . 'helpers' . DS . 'route.php');
        $article = JTable::getInstance('Content');
        $article->load($articleId);
        $article->slug = $article->alias ? ($article->id . ':' . $article->alias) : $article->id;

        return JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid));
    }
}
