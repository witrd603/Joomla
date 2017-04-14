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

<div class="profile-actions">
    <button onclick="Joomla.submitbutton('user.saveprofile')" class="btn btn-success btn-small">Save changes</button>
    <a href="index.php?option=com_lovefactory&view=user&layout=edit&user_id=<?php echo $this->item->user_id; ?>&mode=viewable"
       class="btn btn-danger btn-small">Close</a>
</div>

<?php echo $this->renderer->render($this->page); ?>

<div class="profile-actions">
    <button onclick="Joomla.submitbutton('user.saveprofile')" class="btn btn-success btn-small">Save changes</button>
    <a href="index.php?option=com_lovefactory&view=user&layout=edit&user_id=<?php echo $this->item->user_id; ?>&mode=viewable"
       class="btn btn-danger btn-small">Close</a>
</div>
