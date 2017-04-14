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

class BackendModelPrice extends FactoryModel
{
    function __construct()
    {
        parent::__construct();

        $cids = JFactory::getApplication()->input->get('cid', array(0), 'array');

        $this->setId((int)$cids[0]);
    }

    function setId($id)
    {
        $this->_id = $id;
        $this->_data = null;
    }

    function &getData()
    {
        if (empty($this->_data)) {
            $query = ' SELECT * FROM #__lovefactory_pricing'
                . ' WHERE id = ' . $this->_id;
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();

            if ($this->_data) {
                $this->_data->_gender_prices = $this->_prepareGenderPrices($this->_data->gender_prices);
            }
        }

        if (!$this->_data) {
            $this->_data = $this->getTable();
        }

        return $this->_data;
    }

    public function getGenders()
    {
        JLoader::register('LoveFactoryField', LoveFactoryApplication::getInstance()->getPath('component_administrator') . DS . 'lib' . DS . 'fields' . DS . 'field.php');
        $table = JTable::getInstance('Field', 'Table');

        if (!$table->load(array('type' => 'Gender'))) {
            return array();
        }

        $field = LoveFactoryField::getInstance($table->type, $table);

        return $field->getChoices();
    }

    function getMemberships($only_published = 1)
    {
        $where = ($only_published) ? ' WHERE m.published = 1' : '';

        $query = ' SELECT m.*'
            . ' FROM #__lovefactory_memberships m'
            . $where
            . ' ORDER BY m.ordering ASC';
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    function store()
    {
        $price = $this->getTable();
        $data = $this->_prepareData();

        try {
            $price->save($data);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }

        $this->_id = $price->id;

        return true;
    }

    function delete()
    {
        $cids = JFactory::getApplication()->input->get('cid', array(), 'array');
        $price = $this->getTable();

        foreach ($cids as $cid) {
            if (!$price->delete($cid)) {
                $this->setError($price->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    function change()
    {
        $database = JFactory::getDBO();
        $field = JFactory::getApplication()->input->getString('field');
        $value = JFactory::getApplication()->input->getInt('value');
        $id = JFactory::getApplication()->input->getInt('id');

        if (!in_array($field, array('published', 'required'))) {
            return false;
        }

        $query = ' UPDATE #__lovefactory_fields'
            . ' SET ' . $field . ' = ' . $value
            . ' WHERE id = ' . $id;
        $database->setQuery($query);

        return $database->execute();
    }

    function publish()
    {
        $cids = JFactory::getApplication()->input->get('cid', array(), 'array');
        $price = $this->getTable('price');

        foreach ($cids as $cid) {
            $price->load($cid);

            $price->published = 1;
            $price->store();
        }

        return true;
    }

    function unpublish()
    {
        $cids = JFactory::getApplication()->input->get('cid', array(), 'array');
        $price = $this->getTable('price');

        foreach ($cids as $cid) {
            JFactory::getApplication()->input->set('id', $cid);
            $price->load($cid);

            $price->published = 0;
            $price->store();
        }

        return true;
    }

    function _prepareData()
    {
        JLoader::register('JHtmlLoveFactory', JPATH_SITE . '/components/com_lovefactory/lib/html/html.php');
        $data = JFilterInput::getInstance()->clean($_POST, null);
        $settings = new LovefactorySettings();

        if ($settings->gender_pricing) {
            $genders = $this->getGenders();
            $array = array();

            foreach ($genders as $i => $gender) {
                $value = JHtml::_('LoveFactory.currency', $data['price_' . $i]);
                $array[] = $i . '=' . $value;
            }

            $data['gender_prices'] = implode("\n", $array);
        }

        return $data;
    }

    function _prepareGenderPrices($gender_prices)
    {
        $array = array();
        $prices = explode("\n", $gender_prices);

        if ('' != $gender_prices && count($prices)) {
            foreach ($prices as $price) {
                $price = explode('=', $price);
                $array[$price[0]] = $price[1];
            }
        }

        return $array;
    }
}
