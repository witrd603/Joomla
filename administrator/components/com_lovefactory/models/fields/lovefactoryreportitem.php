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

class JFormFieldLoveFactoryReportItem extends JFormField
{
    public $type = 'LoveFactoryReportItem';

    protected function getInput()
    {
        $html = array();

        $element = $this->form->getValue('element');
        $type = $this->form->getValue('type');
        $item = $this->form->getValue('reported_id');

        $html[] = '<div style="float: left; margin-top: 5px;">';
        $html[] = $this->getReportedItem($element, $type, $item);
        $html[] = '</div>';

        return implode("\n", $html);
    }

    protected function getReportedItem($element, $type, $item)
    {
        switch ($element) {
            case 'message':
                $table = JTable::getInstance('LoveFactoryMessage', 'Table');
                $table->load($item);

                $output = '<textarea disabled="disabled" rows="5">' . $table->text . '</textarea>';
                break;

            case 'profile':
                $user = JFactory::getUser($item);
                $output = '<a href="index.php?option=com_lovefactory&controller=user&task=view&id=' . $item . '" target="_blank">' . $user->username . '</a>';
                break;

            case 'item_comment':
                $table = JTable::getInstance('ItemComment', 'Table');
                $table->load($item);

                $output = '<textarea disabled="disabled" rows="5">' . $table->message . '</textarea>';
                break;

            case 'photo':
                $table = JTable::getInstance('Photo', 'Table');
                $table->load($item);

                $output = '<a href="' . $table->getSource(false) . '" target="_blank"><img src="' . $table->getSource(true) . '" /></a>';
                break;

            case 'video':
                $table = JTable::getInstance('LoveFactoryVideo', 'Table');
                $table->load($item);

                $output = $table->code;
                break;

            case 'group_post':
                $table = JTable::getInstance('GroupPost', 'Table');
                $table->load($item);

                $output = '<textarea disabled="disabled" rows="5">' . $table->text . '</textarea>';
                break;

            case 'group_thread':
                $table = JTable::getInstance('GroupThread', 'Table');
                $table->load($item);

                $output = '<textarea disabled="disabled" rows="5">' . $table->text . '</textarea>';
                break;

            case 'group':
                $table = JTable::getInstance('Group', 'Table');
                $table->load($item);

                $output = '<a href="index.php?option=com_lovefactory&controller=group&task=edit&cid[]=' . $item . '" target="_blank">' . $table->title . '</a>';
                break;

            default:
                $output = '-';
                break;
        }

        return $output;
    }
}
