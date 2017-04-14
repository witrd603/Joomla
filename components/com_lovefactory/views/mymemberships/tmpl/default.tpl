{extends "layout.tpl"}

{block "content"}
    <table class="table table-striped">
        {colgroup cols="|300|100"}

        <thead>
        <tr>
            <th>{text text='mymemberships_list_membership_title'}</th>
            <th>{text text='mymemberships_list_interval_title'}</th>
            <th>{text text='mymemberships_list_status_title'}</th>
        </tr>
        </thead>

        <tbody>
        {foreach $items as $item}
            <tr>
                <td>{$item->title}</td>

                <td>
                    {jhtml _='LoveFactory.format_date' date=$item->start_membership}
                    -
                    {jhtml _='LoveFactory.format_date' date=$item->end_membership mode='date' format='Y-m-d H:i:s' prefix='membership'}
                </td>

                <td>
                    {if $item->expired}
                        <span class="label label-important">{text text='mymemberships_expired'}</span>
                    {else}
                        <span class="label label-success">{text text='mymemberships_active'}</span>
                    {/if}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    {strip}
        <div class="pagination">
            {$pagination->getPagesLinks()}
        </div>
    {/strip}
{/block}
