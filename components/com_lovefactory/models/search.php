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

class FrontendModelSearch extends FactoryModel
{
    protected $page = null;

    public function getPage($page = 'search_quick', $mode = 'search')
    {
        if (is_null($this->page)) {
            $this->page = LoveFactoryPage::getInstance($page, $mode);
            $this->page->bind($this->getRequest());

            $settings = LoveFactoryApplication::getInstance()->getSettings();
            $helper = new ThePhpFactory\LoveFactory\Helper\OppositeGender($settings);
            if ($helper->isOppositeGenderSearchEnabled(JFactory::getUser())) {
                $this->page->removeGenderFields();
            }
        }

        return $this->page;
    }

    public function getRequest()
    {
        $page = $this->getPage();
        $request = JFactory::getApplication()->input->get($page->getFormControl(), array(), 'array');

        if (!$request) {
            $request = JFactory::getApplication()->input->get('module_search', array(), 'array');
        }

        return $request;
    }

    public function getViewResults()
    {
        $request = $this->getRequest();

        if (!$request) {
            return '';
        }

        JLoader::register('FrontendViewResults', LoveFactoryApplication::getInstance()->getPath('component_site') . DS . 'views' . DS . 'results' . DS . 'view.html.php');
        $model = JModelLegacy::getInstance('Results', 'FrontendModel', array(
            'fields' => $this->getPage()->getFields(),
            'request' => $request,
        ));
        $view = new FrontendViewResults();

        $view->setModel($model, true);

        ob_start();
        $view->display();
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }

    public function getJumpToResults()
    {
        $jump = LoveFactoryApplication::getInstance()->getSettings()->search_jump_to_results;

        if (!$jump) {
            return '';
        }

        return '#results';
    }

    public function saveSearch($data, $type = 'search')
    {
        $data = json_decode($data);
        $data = array('form' => $data);

        $table = JTable::getInstance('Search', 'Table');

        $table->load(array(
            'user_id' => JFactory::getUser()->id,
            'type' => $type,
        ));

        $table->save(array(
            'search' => json_encode($data),
            'user_id' => JFactory::getUser()->id,
            'type' => $type,
        ));

        return true;
    }

    public function removeSearch($type = 'search')
    {
        $table = JTable::getInstance('Search', 'Table');

        $table->load(array(
            'user_id' => JFactory::getUser()->id,
            'type' => $type,
        ));

        $table->delete();

        return true;
    }

    public function getSavedSearch($type = null)
    {
        if (null === $type) {
            $type = JFactory::getApplication()->input->getString('view');
        }

        $table = JTable::getInstance('Search', 'Table');
        $table->load(array(
            'user_id' => JFactory::getUser()->id,
            'type' => $type,
        ));

        if (null === $table->search) {
            return null;
        }

        return FactoryRoute::view($type . '&' . http_build_query(json_decode($table->search)));
    }

    public function getUri()
    {
        return clone JUri::getInstance();
    }

    public function getUser()
    {
        return JFactory::getUser();
    }
}
