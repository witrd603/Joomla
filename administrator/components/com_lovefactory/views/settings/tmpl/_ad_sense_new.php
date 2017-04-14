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

?>

<table class="paramlist admintable">
    <!-- adsense_title -->
    <tr>
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="adsense_title"><?php echo JText::_('SETTINGS_ADSENSE_TITLE'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input id="adsense_title" name="adsense_title" type="text"/>
        </td>
    </tr>

    <!-- adsense_scipt -->
    <tr>
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="adsense_script"><?php echo JText::_('SETTINGS_ADSENSE_SCRIPT'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <textarea id="adsense_script" name="adsense_script" cols="30" rows="10"></textarea>
        </td>
    </tr>

    <!-- adsense_rows -->
    <tr>
        <td width="40%" class="paramlist_key">
      <span class="editlinktip">
        <label for="adsense_rows"><?php echo JText::_('SETTINGS_ADSENSE_REPEAT'); ?></label>
      </span>
        </td>
        <td class="paramlist_value">
            <input id="adsense_rows" name="adsense_rows" type="text"/>
        </td>
    </tr>
</table>

<a href="#" class="btn" id="adsense-save"><?php echo JText::_('SETTINGS_ADSENSE_NEW'); ?></a>
