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

class LoveFactoryHelper
{
    public static function convertImperialHeightToMetric($feet, $inches)
    {
        return ceil(($feet * 12 + $inches) * 2.54);
    }

    public static function convertMetricHeightToImperial($cm)
    {
        $inches = $cm * .3937008;

        $feet = floor($inches / 12);
        $inches = $inches % 12;

        return array($feet, $inches);
    }

    public static function convertInchesToCm($inches)
    {
        return ceil($inches * 2.54);
    }

    public static function addSubmenu()
    {
        $approvals = false;
        $settings = (array)LoveFactoryApplication::getInstance()->getSettings();
        foreach ($settings as $key => $value) {
            if (0 === strpos($key, 'approval_')) {
                if ($value) {
                    $approvals = true;
                    break;
                }
            }
        }

        $items = array(
            'users' => 'SUBMENU_USERS',
            'groups' => 'SUBMENU_GROUPS',
            'approvals' => 'SUBMENU_APPROVALS',
            'orders' => 'SUBMENU_ORDERS',
            'payments' => 'SUBMENU_PAYMENTS',
            'invoices' => 'SUBMENU_INVOICES',
            'reports' => 'SUBMENU_REPORTS',
            'configuration' => 'SUBMENU_SETTINGS',
            'about' => 'SUBMENU_ABOUT',
        );

        if (!$approvals) {
            unset($items['approvals']);
        }

        $task = JFactory::getApplication()->input->getString('task', '');
        $view = JFactory::getApplication()->input->getString('view', '');
        $currentTask = ('' == $task || 'undefined' == $task) ? $view : $task;

        foreach ($items as $task => $title) {
            //JSubMenuHelper::addEntry(JText::_($title), 'index.php?option=com_lovefactory&task=' . $task, $currentTask == $task);

            JHtmlSidebar::addEntry(
                JText::_($title),
                'index.php?option=com_lovefactory&task=' . $task,
                $currentTask == $task
            );
        }
    }

    public static function addFormLabels($form, $namespace = null)
    {
        if (null === $namespace) {
            $namespace = $form->getFormControl();
        }

        // Set the labels and descriptions in case they are not set.
        foreach ($form->getFieldsets() as $fieldset) {
            foreach ($form->getFieldset($fieldset->name) as $field) {
                $label = $form->getFieldAttribute($field->fieldname, 'label', '', $field->group);
                $desc = $form->getFieldAttribute($field->fieldname, 'description', '', $field->group);
                $base = $namespace . '_' . ($field->group ? $field->group . '_' : '') . $field->fieldname;

                $base = str_replace(array('.', '[', ']'), array('_', '_', ''), $base);

                if ('' == $label) {
                    $label = FactoryText::_($base . '_label');
                    $form->setFieldAttribute($field->fieldname, 'label', $label, $field->group);
                }

                if ('' == $desc) {
                    $desc = FactoryText::_($base . '_desc');
                    $form->setFieldAttribute($field->fieldname, 'description', $desc, $field->group);
                }
            }
        }
    }

    public static function prepareMapMembers($results, $showGrouped = false)
    {
        $array = array();

        if (!$results) {
            return $array;
        }

        foreach ($results as $result) {
            $hash = md5($result->lat . $result->lng);

            if (!isset($array[$hash])) {
                $array[$hash] = array();
            }

            $array[$hash][] = $result;
        }

        $prepared = array();

        foreach ($array as $item) {
            if (1 == count($item)) {
                $member = $item[0];

                $prepared[] = (object)array(
                    'type' => 'single.link',
                    'label' => $member->username,
                    'link' => FactoryRoute::view('profile&user_id=' . $member->user_id),
                    'user_id' => $member->user_id,
                    'lat' => $member->lat,
                    'lng' => $member->lng,
                );
            } elseif ($showGrouped) {
                $members = array();
                foreach ($item as $member) {
                    $members[] = (object)array(
                        'label' => $member->username,
                        'link' => FactoryRoute::view('profile&user_id=' . $member->user_id),
                        'user_id' => $member->user_id,
                    );
                }

                $prepared[] = (object)array(
                    'type' => 'multiple.group',
                    'label' => FactoryText::plural('maps_users_grouped_label', count($item)),
                    'count' => count($members),
                    'members' => $members,
                    'lat' => $member->lat,
                    'lng' => $member->lng,
                );
            } else {
                $members = count($item);

                $ids = array();
                foreach ($item as $member) {
                    $ids[] = $member->user_id;
                }

                $prepared[] = (object)array(
                    'type' => 'multiple.link',
                    'label' => FactoryText::plural('maps_users_grouped_label', count($item)),
                    'count' => $members,
                    'link' => FactoryRoute::view('groupedmembers&members=' . implode(',', $ids)),
                    'lat' => $member->lat,
                    'lng' => $member->lng,
                );
            }
        }

        return $prepared;
    }

    public static function getUserProfile($userId = null)
    {
        static $profiles = array();

        if (null === $userId) {
            $userId = JFactory::getUser()->id;
        }

        if (!isset($profiles[$userId])) {
            $table = JTable::getInstance('Profile', 'Table');
            $table->load($userId);

            $profiles[$userId] = $table;
        }

        return $profiles[$userId];
    }

    public static function getUserProfileFromRequest($var = 'user_id')
    {
        $userId = JFactory::getApplication()->input->getInt($var, null);

        return self::getUserProfile($userId);
    }
}
