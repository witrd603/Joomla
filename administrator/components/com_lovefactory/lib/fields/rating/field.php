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

class LoveFactoryFieldRating extends LoveFactoryField
{
    protected $accessPageWhiteList = array('profile_results', 'profile_view', 'friends_view', 'profile_map');

    public function renderInputView()
    {
        if (!floatval($this->data)) {
            return FactoryText::_('field_rating_no_votes');
        }

        if (0 == $this->params->get('view_mode', 1)) {
            return '<i class="factory-icon icon-star"></i>' . $this->data;
        }

        $html = array();

        $html[] = '<div class="lovefactory-field-rating">';

        for ($i = 0; $i < 10; $i++) {
            $difference = $this->data - $i;

            if ($difference <= 0) {
                $class = 'star-empty';
            } elseif ($difference >= .5 && $difference <= 1) {
                $class = 'star-half';
            } else {
                $class = 'star';
            }

            $html[] = '<i class="factory-icon icon-' . $class . '"></i>';
        }

        $html[] = '</div>';

        return implode('', $html);
    }

    public function getId()
    {
        return 'rating';
    }
}
