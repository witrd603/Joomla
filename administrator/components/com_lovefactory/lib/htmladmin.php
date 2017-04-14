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

abstract class JHtmlLoveFactoryAdministrator
{
    public static function featured($value = 0, $i, $prefix, $canChange = true)
    {
        $states = array(
            0 => array('disabled.png', $prefix . 'featured', 'COM_SOCIALFACTORY_UNFEATURED', 'COM_SOCIALFACTORY_CLICK_TO_FEATURE'),
            1 => array('featured.png', $prefix . 'unfeatured', 'COM_SOCIALFACTORY_FEATURED', 'COM_SOCIALFACTORY_CLICK_TO_UNFEATURE'),
        );

        $state = JArrayHelper::getValue($states, (int)$value, $states[1]);
        $html = JHtml::_('image', 'admin/' . $state[0], JText::_($state[2]), NULL, true);

        if ($canChange) {
            $html = '<a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\', \'' . $state[1] . '\')" title="' . JText::_($state[3]) . '">' . $html . '</a>';
        }

        return $html;
    }

    public static function orderStatus($value = 0, $i, $prefix, $canChange = true)
    {
        $states = array(
            10 => array('icon-16-notice-note.png', $prefix . 'featured', 'Pending', 'Click to mark as processed'),
            20 => array('tick.png', $prefix . 'unfeatured', 'Processed', 'Click to mark as pending'),
            30 => array('publish_r.png', $prefix . 'unfeatured', 'Processed', 'Click to mark as pending'),
            40 => array('publish_g.png', $prefix . 'unfeatured', 'Processed', 'Click to mark as pending'),
        );

        $state = JArrayHelper::getValue($states, (int)$value, $states[10]);
        $html = JHtml::_('image', 'admin/' . $state[0], JText::_($state[2]), NULL, true);

        if ($canChange) {
            $html = '<a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\', \'' . $state[1] . '\')" title="' . JText::_($state[3]) . '">' . $html . '</a>';
        }

        return $html;
    }

    public static function orderPaid($value = 0, $i, $prefix, $canChange = true)
    {
        $states = array(
            0 => array('disabled.png', $prefix . 'featured', 'Not paid', 'Click to mark as paid'),
            1 => array('icon-16-allow.png', $prefix . 'unfeatured', 'Paid', 'Click to mark as not paid'),
        );

        $state = JArrayHelper::getValue($states, (int)$value, $states[0]);
        $html = JHtml::_('image', 'admin/' . $state[0], JText::_($state[2]), NULL, true);

        if ($canChange) {
            $html = '<a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\', \'' . $state[1] . '\')" title="' . JText::_($state[3]) . '">' . $html . '</a>';
        }

        return $html;
    }

    public static function orderLabel($status)
    {
        $statuses = array(
            10 => 'warning',
            20 => 'success',
            30 => 'important',
            40 => 'info',
        );

        $labels = array(
            10 => JText::_('COM_LOVEFACTORY_PAYMENT_STATUS_PENDING'),
            20 => JText::_('COM_LOVEFACTORY_PAYMENT_STATUS_COMPLETED'),
            30 => JText::_('COM_LOVEFACTORY_PAYMENT_STATUS_FAILED'),
            40 => JText::_('COM_LOVEFACTORY_PAYMENT_STATUS_MANUAL_CHECK'),
        );

        return self::label($statuses[$status], $labels[$status]);
    }

    public static function reportLabel($status)
    {
        $statuses = array(
            1 => 'success',
            0 => 'important',
        );

        $labels = array(
            1 => JText::_('COM_LOVEFACTORY_REPORT_STATUS_RESOLVED'),
            0 => JText::_('COM_LOVEFACTORY_REPORT_STATUS_UNRESOLVED'),
        );

        return self::label($statuses[$status], $labels[$status]);
    }

    protected static function label($badge, $label)
    {
        $html = array();

        $html[] = '<span class="factory-badge badge-' . $badge . '">';
        $html[] = $label;
        $html[] = '</span>';

        return implode("\n", $html);
    }
}
