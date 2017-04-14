{extends "layout.tpl"}

{block "content"}
    <table>
        <tbody>
        <tr>
            <th>{jtext _='mymembership_membership_title'}</th>
            <td>{$item->title}</td>
        </tr>

        {if isset($item->start_membership)}
            <tr>
                <th><i class="factory-icon icon-clock"></i>{jtext _='mymembership_membership_started_on'}</th>
                <td>
                    <abbr title="{$item->start_membership}">
                        {jhtml _='LoveFactory.date' date=$item->start_membership}
                    </abbr>
                </td>
            </tr>
        {/if}

        {if isset($item->end_membership)}
            <tr>
                <th><i class="factory-icon icon-clock"></i>{jtext _='mymembership_membership_ends_on'}</th>
                <td>
                    <abbr title="{$item->end_membership}">
                        {jhtml _='LoveFactory.date' date=$item->end_membership}
                    </abbr>
                </td>
            </tr>
        {/if}

        {foreach $features as $feature => $key}
            <tr>
                <th>
                    <i class="factory-icon icon-membership-feature-{$feature}"></i>
                    {jtext _="memberships_membership_features_"|cat:$feature}
                </th>

                <td>
                    {mymembership_feature feature=$feature features=$item->features statistics=$statistics}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <div class="actions">
        <a href="{route view="memberships"}" class="btn btn-small btn-primary">
            <i class="factory-icon icon-medal"></i>{jtext _='mymembership_upgrade'}</a>

        <a href="{route view="mymemberships"}" class="btn btn-small">
            <i class="factory-icon icon-box"></i>{jtext _='mymembership_memberships_archive'}</a>
    </div>
{/block}
