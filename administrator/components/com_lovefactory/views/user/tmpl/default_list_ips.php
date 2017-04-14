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

<div class="slider-table-wrapper">
    <table class="slider-table">
        <thead>
        <tr>
            <th><?php echo FactoryText::_('user_ips_title_ip'); ?></th>
            <th><?php echo FactoryText::_('user_ips_title_last_access'); ?></th>
            <th><?php echo FactoryText::_('user_ips_title_page_views'); ?></th>
            <th><?php echo FactoryText::_('user_ips_title_sharing_users'); ?></th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($this->ips as $this->i => $this->ip): ?>
            <tr class="<?php echo $this->i % 2 ? 'alternate' : ''; ?>">
                <td class="center"><?php echo $this->ip->ip; ?></td>
                <td class="center"><?php echo JHtml::date($this->ip->updated_at, 'Y-m-d H:i:s'); ?></td>
                <td class="center"><?php echo $this->ip->visits; ?></td>
                <td class="center"><?php echo $this->ip->shares; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
