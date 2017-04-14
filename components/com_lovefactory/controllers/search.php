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

class FrontendControllerSearch extends FrontendController
{
    function search()
    {
        $model = $this->getModel('search', 'FrontendModel');

        if ('POST' == JFactory::getApplication()->input->getMethod()) {
            $model->updateSearchQuery();
        }

        $type = JFactory::getApplication()->input->getString('type', 'quicksearch');

        $settings = new LovefactorySettings();
        $jump = $settings->jump_to_results ? '#lovefactory-results' : '';

        // Reset limitstart
        $limitstart = JFactory::getApplication()->input->cookie->getInt('lovefactory_search_limitstart', 0);
        if ($limitstart) {
            setcookie('lovefactory_search_limitstart', 0, null, '/');
        }

        $Itemid = JFactory::getApplication()->input->getInt('Itemid', 0);
        $this->setRedirect(JRoute::_('index.php?option=com_lovefactory&view=' . $type . '&show=1&Itemid=' . $Itemid . $jump, false));
    }

    function changesort()
    {
        $lovefactory_search_limitstart = JFactory::getApplication()->input->cookie->getInt('lovefactory_search_limitstart', 0);
        JFactory::getApplication()->input->set('limitstart', $lovefactory_search_limitstart);

        $model = $this->getModel('search', 'FrontendModel');
        $model->updateSortQuery();

        $type = JFactory::getApplication()->input->getString('type', 'quicksearch');
        $view = $this->getView($type, 'raw', 'FrontendView');

        $view->display();

//	  $type = JRequest::getVar('type', 'quicksearch', 'REQUEST', 'string');
//
//	  $Itemid = JRequest::getVar('Itemid', 0, 'REQUEST', 'integer');
//	  $this->setRedirect('index.php?option=com_lovefactory&view='.$type.'&format=raw&show=1&limitstart='.$lovefactory_search_limitstart);
    }

    public function save()
    {
        $search = $this->input->post->getString('search');
        $type = $this->input->post->getString('type');
        $redirect = $this->input->post->getString('redirect');

        /** @var FrontendModelSearch $model */
        $model = $this->getModel('Search');

        if ($model->saveSearch($search, $type)) {
            $msg = FactoryText::_('search_task_save_success');
            $type = 'message';
        } else {
            $msg = FactoryText::_('search_task_save_error');
            $type = 'error';
        }

        $this->setRedirect($redirect, $msg, $type);
    }

    public function remove()
    {
        $type = $this->input->post->getString('type');
        $redirect = $this->input->post->getString('redirect');

        /** @var FrontendModelSearch $model */
        $model = $this->getModel('Search');

        if ($model->removeSearch($type)) {
            $msg = FactoryText::_('search_task_remove_success');
            $type = 'message';
        } else {
            $msg = FactoryText::_('search_task_remove_error');
            $type = 'error';
        }

        $this->setRedirect($redirect, $msg, $type);
    }
}
