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

class LoveFactoryFieldLastSeen extends LoveFactoryField
{
    protected $accessPageWhiteList = array('profile_results', 'profile_view', 'friends_view', 'profile_map');

    public function renderInputView()
    {
        if (JFactory::getDbo()->getNullDate() == $this->data) {
            return FactoryText::_('field_last_seen_never');
        }

        return JHtml::_('LoveFactory.format_date', $this->data, 'ago');
    }

    public function getId()
    {
        return 'lastvisit';
    }
}
