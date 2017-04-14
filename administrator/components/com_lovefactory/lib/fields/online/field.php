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

class LoveFactoryFieldOnline extends LoveFactoryField
{
    protected $accessPageBlackList = array('registration', 'profile_edit', 'profile_fillin');

    public function renderInputView()
    {
        $html = array();

        if ($this->data) {
            $html[] = '<i class="factory-icon icon-status"></i>' . FactoryText::_('field_online_view_online_label');
        } else {
            $html[] = '<i class="factory-icon icon-status-offline"></i>' . FactoryText::_('field_online_view_offline_label');
        }

        return implode("\n", $html);
    }

    public function renderInputSearch()
    {
        $choices = array(1 => FactoryText::_('field_online_search_checkbox_label'));
        $html = array();

        $html[] = LoveFactoryFieldMultipleChoiceInterface::renderEditCheckbox($choices, $this->getHtmlName(), $this->data, $this->getHtmlId());

        return implode("\n", $html);
    }

    public function bind($data)
    {
        if ('view' != $this->mode) {
            return parent::bind($data);
        }

        $this->data = false;

        if ($data->loggedin &&
            (JFactory::getDate()->toUnix() - $data->lastvisit < (JFactory::getConfig()->get('lifetime') * 60))
        ) {
            $this->data = true;
        }
    }

    public function getQuerySearchCondition($query)
    {
        if (is_null($this->data) || !is_array($this->data) || !$this->data || !in_array(1, $this->data)) {
            return false;
        }

        return '(' . $query->quoteName('p.loggedin') . ' = ' . $query->quote(1) . ' AND ' . $query->quote(JFactory::getDate()->toUnix()) . ' - ' . $query->quoteName('p.lastvisit') . ' < ' . JFactory::getConfig()->get('lifetime') * 60 . ')';
    }
}
