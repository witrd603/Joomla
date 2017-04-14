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

<style>
    .icon-48-generic {
        background-image: url(<?php echo JURI::root(); ?>components/com_lovefactory/assets/images/love.png);
    }

    .icon-32-database {
        background-image: url(<?php echo JURI::root(); ?>components/com_lovefactory/assets/images/database.png);
        background-position: center;
        width: 32px !important;
    }
</style>

<script type="text/javascript">
    Joomla.submitbutton = function (pressbutton) {
        if ('edit' == pressbutton) {
            document.getElementById('controller').value = 'user';
        } else {
            document.getElementById('controller').value = 'users';
        }
        Joomla.submitform(pressbutton);
    }
</script>

<form action="index.php?option=com_lovefactory&view=users" method="post" name="adminForm" id="adminForm">

    <table width="100%">
        <tr>
            <td align="left" width="50%">
                <label for="search" style="display: inline;"><?php echo JText::_('USERS_FILTER'); ?>:</label>
                <input type="text" id="search" name="search" id="search" value="<?php echo $this->lists['search']; ?>"
                       class="text_area" onchange="document.adminForm.submit();"/>
                <button onclick="this.form.submit();"><?php echo JText::_('USERS_GO'); ?></button>
                <button
                    onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.getElementById('membership').value='-1';this.form.getElementById('banned').value='-1';this.form.submit();"><?php echo JText::_('USERS_RESET'); ?></button>
            </td>
            <td style="text-align: right;">
                <?php echo JHTML::_('select.genericlist', $this->lists['memberships'], 'membership', 'size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->lists['membership']); ?>
                <?php echo JHTML::_('select.genericlist', $this->lists['bans'], 'banned', 'size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->lists['banned']); ?>
            </td>
        </tr>
    </table>

    <table class="adminlist table table-striped table-hover">
        <thead>
        <tr>
            <th width="20px"><?php echo JText::_('NUM'); ?></th>
            <th width="20px"><input type="checkbox" name="checkall-toggle" value=""
                                    title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
                                    onclick="Joomla.checkAll(this)"/></th>
            <th class="title"><?php echo JHTML::_('grid.sort', JText::_('USERS_USERNAME'), 'u.username', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="10%"><?php echo JHTML::_('grid.sort', JText::_('USERS_PROFILE_PRIVACY'), 'p.online', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="10%"><?php echo JHTML::_('grid.sort', JText::_('USERS_PROFILE_FILLED'), 'p.filled', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="14%"><?php echo JHTML::_('grid.sort', JText::_('USERS_REGISTRATION_DATE'), 'p.date', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="10%"><?php echo JHTML::_('grid.sort', JText::_('USERS_MEMBERSHIP'), 's.membership_id', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            <th width="5%"><?php echo JHTML::_('grid.sort', JText::_('USERS_BANNED'), 'p.banned', $this->lists['order_Dir'], $this->lists['order']); ?></th>
        </tr>
        </thead>

        <tfoot>
        <tr>
            <td colspan="12">
                <?php echo $this->pagination->getLimitBox(); ?><?php echo $this->pagination->getListFooter(); ?>
            </td>
        </tr>
        </tfoot>

        <tbody>
        <?php foreach ($this->users as $i => $user): ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td width="20px"><?php echo($i + 1 + $this->pagination->limitstart); ?></td>
                <td width="20px"><?php echo JHTML::_('grid.id', $i, $user->user_id); ?></td>
                <td>
                    <a href="<?php echo JRoute::_('index.php?option=com_lovefactory&controller=user&task=edit&user_id=' . $user->user_id); ?>">
                        <?php echo $user->username; ?>
                    </a>
                </td>

                <td style="text-align: center;">
                    <?php if (0 == $user->online): ?>
                        <span class="label label-success">online</span>
                    <?php elseif (1 == $user->online): ?>
                        <span class="label label-warning">friends</span>
                    <?php else: ?>
                        <span class="label label-important">private</span>
                    <?php endif; ?>
                </td>

                <td class="center">
                    <?php if ($user->filled): ?>
                        <span class="label label-success">filled</span>
                    <?php else: ?>
                        <span class="label label-important">not filled</span>
                    <?php endif; ?>
                </td>

                <td><?php echo $user->date; ?></td>

                <td style="line-height: normal;">
                    <?php if ($user->membership_sold_id): ?>
                        <?php echo $user->membership_title; ?>
                    <?php else: ?>
                        <?php echo $this->defaultMembership->title; ?>
                    <?php endif; ?>

                    <div style="color: #999999; font-size: 11px;">
                        <?php if ($user->end_membership == JFactory::getDbo()->getNullDate() || null === $user->end_membership): ?>
                            <?php echo JText::_('USERS_UNLIMITED'); ?>
                        <?php else: ?>
                            <?php echo JHtml::date($user->end_membership, 'Y-m-d H:i'); ?>
                        <?php endif; ?>
                    </div>
                </td>

                <td style="text-align: center;" class="jgrid">
                    <?php echo JHtml::_('jgrid.published', $user->banned, $i, '', false); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <input type="hidden" name="controller" value="" id="controller"/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
    <?php echo JHtml::_('form.token'); ?>
</form>

<script>
    Joomla.submitbutton = function (pressbutton) {

        if ('options' == pressbutton) {
            SqueezeBox.initialize({});

            var options = $merge(options || {}, "{handler: 'iframe', size: {x: 800, y: 450}}");
            SqueezeBox.setOptions(SqueezeBox.presets, options);
            SqueezeBox.assignOptions();
            SqueezeBox.setContent('iframe', 'index.php?option=com_lovefactory&task=usersoptions&tmpl=component');
            return false;
        }

        Joomla.submitform(pressbutton);
    }

    Joomla.submitbutton = function (pressbutton) {
        if ('edit' == pressbutton) {
            document.getElementById('controller').value = 'user';
        } else {
            document.getElementById('controller').value = 'users';
        }
        Joomla.submitform(pressbutton);
    }
</script>
