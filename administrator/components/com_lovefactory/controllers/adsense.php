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

class BackendControllerAdsense extends BackendController
{
    function getList()
    {
        $model = $this->getModel('adsense');

        $list = $model->getData();

        ?>

        <table class="adminlist table table-striped table-hover">

        <thead>
        <tr>
            <td><?php echo JText::_('Title'); ?></td>
            <td width="200px"><?php echo JText::_('Script'); ?></td>
            <td width="200px"><?php echo JText::_('Repeat every x rows'); ?></td>
            <td width="200px"></td>
        </tr>
        </thead>

        <tbody>

        <?php
        foreach ($list as $item) {
            echo '<tr>';
            echo '  <td>' . $item->title . '</td>';
            echo '  <td>' . $item->script . '</td>';
            echo '  <td>' . $item->rows . '</td>';
            echo '  <td><a href="#" class="adsense-delete" rel="' . $item->id . '">delete</a></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        die();
    }

    function save()
    {
        $model = $this->getModel('adsense');
        $model->save();
    }

    function delete()
    {
        $model = $this->getModel('adsense');
        $model->delete();
    }
}
