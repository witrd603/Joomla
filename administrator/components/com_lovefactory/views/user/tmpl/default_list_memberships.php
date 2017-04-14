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

<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th style="width: 60px;">Active</th>
        <th>Title</th>
        <th style="width: 150px;">Interval</th>
        <th style="width: 140px;">Action</th>
    </tr>
    </thead>

    <tbody>
    <?php foreach ($this->memberships as $membership): ?>
        <tr>
            <td>
                <?php if ($membership->expired): ?>
                    <span class="label label-important">expired</span>
                <?php else: ?>
                    <span class="label label-success">active</span>
                <?php endif; ?>
            </td>

            <td>
                <?php echo $membership->title; ?>
            </td>

            <td class="small muted" style="line-height: normal;">
                <div>
                    <?php echo $membership->start_membership; ?>
                </div>
                &mdash;
                <div>
                    <?php if (JFactory::getDbo()->getNullDate() == $membership->end_membership): ?>
                        Unlimited
                    <?php else: ?>
                        <?php echo $membership->end_membership; ?>
                    <?php endif; ?>
                </div>
            </td>

            <td>
                <?php if ($membership->expired): ?>
                    <a href="<?php echo JRoute::_('index.php?option=com_lovefactory&controller=user&task=activatemembership&id=' . $membership->id); ?>"
                       class="btn btn-small">
                        Mark as active
                    </a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
