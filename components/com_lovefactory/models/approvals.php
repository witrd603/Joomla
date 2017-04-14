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

class FrontendModelApprovals extends FactoryModelList
{
    protected $defaultOrder = 'desc';

    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->sort = array(
            '' => array('text' => FactoryText::_($this->getName() . '_filter_sort_date'), 'column' => 'a.created_at'),
            'status' => array('text' => FactoryText::_($this->getName() . '_filter_sort_status'), 'column' => 'a.approved'),
        );
    }

    protected function getListQuery()
    {
        $query = parent::getListQuery();
        $user = JFactory::getUser();

        $query->select('a.*')
            ->from('#__lovefactory_approvals a')
            ->where('a.user_id = ' . $query->quote($user->id));

        return $query;
    }
}
