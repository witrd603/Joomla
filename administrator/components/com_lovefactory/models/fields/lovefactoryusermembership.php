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

JFormHelper::loadFieldType('Text');

class JFormFieldLoveFactoryUserMembership extends JFormFieldText
{
    public $type = 'LoveFactoryUserMembership';

    protected function getInput()
    {
        if (!$this->value) {
            $table = JTable::getInstance('Membership', 'Table');
            $table->load(array(
                'default' => 1,
            ));
        } else {
            $table = JTable::getInstance('MembershipSold', 'Table');
            $table->load($this->value);
        }

        $value = $table->title . ' (';

        if (!isset($table->end_membership) || $table->end_membership == JFactory::getDbo()->getNullDate()) {
            $value .= FactoryText::_('membership_unlimited');
        } else {
            $value .= JHtml::date($table->end_membership, 'Y-m-d H:i:s');
        }

        $value .= ')';

        $this->value = $value;

        return parent::getInput();
    }
}
