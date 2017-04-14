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

jimport('joomla.application.component.model');

class BackendModelAdsense extends JModelLegacy
{
    function getData()
    {
        $query = ' SELECT a.*'
            . ' FROM #__lovefactory_adsense a';
        $this->_db->setQuery($query);

        $list = $this->_db->loadObjectList();

        foreach ($list as $i => $item) {
            $list[$i]->script = htmlentities($item->script);
        }

        return $list;
    }

    function save()
    {
        $table = $this->getTable('AdSense');
        $table->bind(JFilterInput::getInstance()->clean($_POST, null));
        $table->script = JFactory::getApplication()->input->getRaw('script');

        $table->store();
    }

    function delete()
    {
        $id = JFactory::getApplication()->input->getInt('id');
        $table = $this->getTable('AdSense');
        $table->load($id);
        $table->delete();
    }
}
