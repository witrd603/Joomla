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

class BackendModelMembership extends FactoryModel
{
    var $id;

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
            $this->_data = $this->getTable();
            $this->_data->load($this->_id);
        }

        if (!$this->_data) {
            $this->_data = $this->getTable();
        }

        return $this->_data;
    }

    function publish()
    {
        $cids = JFactory::getApplication()->input->get('cid', array(), 'array');
        $membership = $this->getTable('membership');

        foreach ($cids as $cid) {
            $membership->load($cid);

            $membership->published = 1;
            $membership->store();
        }

        return true;
    }

    function unpublish()
    {
        $cids = JFactory::getApplication()->input->get('cid', array(), 'array');
        $membership = $this->getTable('membership');

        foreach ($cids as $cid) {
            $membership->load($cid);

            if ($membership->default) {
                throw new Exception(JText::_('You cannot unpublish the default membership'));
                return false;
            }

            $membership->published = 0;
            $membership->store();
        }

        return true;
    }

    function delete()
    {
        $cids = JFactory::getApplication()->input->get('cid', array(), 'array');
        $membership = $this->getTable();

        foreach ($cids as $cid) {
            $membership->load($cid);

            if ($membership->default) {
                throw new Exception(JText::_('You cannot delete the default membership!'));
                return false;
            }

            if (!$membership->delete()) {
                return false;
            }
        }
        return true;
    }

    function saveorder()
    {
        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');

        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');
        $order = JFactory::getApplication()->input->get('order', array(), 'array');
        $redirect = JFactory::getApplication()->input->getInt('redirect');
        $rettask = JFactory::getApplication()->input->getCmd('returntask');
        $total = count($cid);
        $conditions = array();

        JArrayHelper::toInteger($cid, array(0));
        JArrayHelper::toInteger($order, array(0));

        // Instantiate an article table object
        $row = JTable::getInstance('membership', 'Table');

        // Update the ordering for items in the cid array
        for ($i = 0; $i < $total; $i++) {
            $row->load(intval($cid[$i]));

            if ($row->ordering != $order[$i]) {
                $row->ordering = $order[$i];

                if (!$row->store()) {
                    throw new Exception($db->getErrorMsg(), 500);
                    return false;
                }
            }
        }

        return true;
    }

    function orderContent($direction)
    {
        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');

        if (isset($cid[0])) {
            $row = JTable::getInstance('membership', 'Table');
            $row->load(intval($cid[0]));
            $row->move($direction);
        }
    }

    function setDefault()
    {
        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');
        JArrayHelper::toInteger($cid);

        if (isset($cid[0]) && $cid[0]) {
            $id = $cid[0];
        } else {
            throw new Exception(JText::_('No Item Selected!'));
        }

        $membership = $this->getTable();
        $membership->load($id);

        if (!$membership->published) {
            throw new Exception(JText::_('The Default Membership Must Be Published!'));
        }

        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->update('#__lovefactory_memberships')
            ->set($dbo->quoteName('default') . ' = ' . $dbo->quote(0));
        $this->_db->setQuery($query);
        $this->_db->execute();

        $membership->default = 1;
        $membership->store();

        return true;
    }

    public function getForm()
    {
        require_once JPATH_SITE . '/components/com_lovefactory/vendor/autoload.php';

        $restrictions = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::getTypes();

        JForm::addFormPath(JPATH_ADMINISTRATOR . '/components/com_lovefactory/models/forms');
        JForm::addFieldPath(JPATH_SITE . '/components/com_lovefactory/lib/ThePhpFactory/LoveFactory/Restrictions/Fields');

        /** @var JForm $form */
        $form = JForm::getInstance('com_lovefactory.membership', 'membership', array(
            'control' => 'membership'
        ));

        foreach ($restrictions as $name) {
            if (!$this->isRestrictionEnabled($name)) {
                continue;
            }

            $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction($name);
            $name = $restriction->getRestrictionName();

            switch (true) {
                case $restriction instanceof \ThePhpFactory\LoveFactory\Restrictions\CountableRestriction:
                    $field = simplexml_load_string(
                        <<<XML
                        <form>
  <fieldset name="restrictions">
    <field type="CountableRestriction" name="$name" required="true" filter="integer" />
  </fieldset>
</form>
XML
                    );
                    $form->setField($field, 'restrictions');
                    break;

                case $restriction instanceof \ThePhpFactory\LoveFactory\Restrictions\BooleanRestriction:
                    $field = simplexml_load_string(
                        <<<XML
                        <form>
  <fieldset name="restrictions">
    <field name="$name" type="radio" default="0" filter="integer" required="true" class="btn-group btn-group-yesno">
      <option value="0">JNO</option>
      <option value="1">JYES</option>
    </field>
  </fieldset>
</form>
XML
                    );
                    $form->setField($field, 'restrictions');
                    break;

                case $restriction instanceof \ThePhpFactory\LoveFactory\Restrictions\ListRestriction:
                    $options = $restriction->getListValues();

                    $html = array();
                    foreach ($options as $key => $value) {
                        $html[] = '<option value="' . $key . '">' . $value . '</option>';
                    }
                    $html = implode("\n", $html);

                    $field = simplexml_load_string(
                        <<<XML
                        <form>
  <fieldset name="restrictions">
    <field name="$name" type="list" default="0" filter="integer" required="true">
      $html
    </field>
  </fieldset>
</form>
XML
                    );
                    $form->setField($field, 'restrictions');
                    break;
            }
        }

        LoveFactoryHelper::addFormLabels($form);

        return $form;
    }

    public function save(array $data = array())
    {
        $this->setState('item.id', isset($data['id']) ? $data['id'] : 0);

        $form = $this->getForm();
        $data = $form->filter($data);
        $return = $form->validate($data);

        if ($return instanceof Exception) {
            $this->setError($return->getMessage());
            return false;
        }

        // Check the validation results.
        if ($return === false) {
            // Get the validation messages from the form.
            $errors = array();
            foreach ($form->getErrors() as $message) {
                $errors[] = $message->getMessage();
            }
            $this->setError(implode('<br />', $errors));
            return false;
        }

        $restrictions = new \Joomla\Registry\Registry($data['restrictions']);
        $data['restrictions'] = $restrictions->toString();

        $membership = JTable::getInstance('Membership', 'Table');
        $membership->save($data);

        JEventDispatcher::getInstance()->trigger('onLoveFactoryMembershipUpdated', array(
            'com_lovefactory.membership.updated', $membership, $data['apply_to_sold']
        ));

        $this->setState('item.id', $membership->id);

        return true;
    }

    private function isRestrictionEnabled($name)
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        switch ($name) {
            case 'blog_factory_access':
                return $settings->enable_blogfactory_integration;
                break;

            case 'chat_factory_access':
                return $settings->enable_chatfactory_integration;
                break;
        }

        return true;
    }
}
