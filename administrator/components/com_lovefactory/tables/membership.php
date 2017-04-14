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

jimport('joomla.language.helper');

class TableMembership extends JTable
{
    var $id = null;
    var $title = null;
    var $published = null;
    var $ordering = null;
    var $default = null;
    var $icon_extension = null;
    var $max_friends = null;
    var $max_photos = null;
    var $max_videos = null;
    var $max_messages_per_day = null;
    var $max_interactions_per_day = null;
    var $shoutbox = null;
    var $chatfactory = null;
    var $top_friends = null;
    var $groups_create = null;
    var $groups_join = null;
    var $same_gender_interaction = null;

    public function __construct(&$db)
    {
        parent::__construct('#__lovefactory_memberships', 'id', $db);
    }

    public function delete($pk = null)
    {
        $this->deleteAssociatedPrices();

        return parent::delete($pk);
    }

    public function deleteAssociatedPrices()
    {
        $query = ' DELETE'
            . ' FROM #__lovefactory_pricing'
            . ' WHERE membership_id = ' . $this->id;
        $this->_db->setQuery($query);
        $this->_db->execute();
    }

    public function hasUsers()
    {
        $query = ' SELECT COUNT(p.user_id)'
            . ' FROM #__lovefactory_profiles p'
            . ' WHERE p.membership = ' . $this->id
            . ' LIMIT 0,1';
        $this->_db->setQuery($query);

        return $this->_db->loadResult();
    }

    public function getShoutboxValues($list = false)
    {
        $array = array(
            0 => JText::_('COM_LOVEFACTORY_SHOUTBOX_NO_ACCESS'),
            1 => JText::_('COM_LOVEFACTORY_SHOUTBOX_ONLY_READ'),
            2 => JText::_('COM_LOVEFACTORY_SHOUTBOX_FULL_ACCESSS'),
        );

        if ($list) {
            foreach ($array as $value => $text) {
                $array[$value] = array('value' => $value, 'text' => $text);
            }
        }

        return $array;
    }

    public function isDefault()
    {
        return 1 == $this->default;
    }

    public function loadDefault()
    {
        $this->load(array(
            'default' => 1
        ));
    }
}
