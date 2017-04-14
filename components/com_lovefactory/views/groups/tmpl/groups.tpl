<table class="table table-striped">
    {colgroup cols="|100,hidden-phone|120"}

    <thead>
    <tr>
        <th></th>
        <th style="text-align: center;" class="hidden-phone">{text text='groups_list_title_members'}</th>
        <th>{text text='groups_list_title_last_activity'}</th>
    </tr>
    </thead>

    <tbody>
    {foreach $items as $item}
        <tr>
            <td>
                <a href="{jroute view='group&id='|cat:$item->id}" style="font-weight: bold;">
                    {$item->title}
                </a>

                <div class="small muted" style="line-height: normal;">
                    {if $approval && !$item->approved}
                        <span class="fa fa-fw fa-exclamation-circle text-danger"></span>
                        {text text='groups_group_pending_approval'}
                    {elseif $item->private}
                        <span class="fa fa-fw fa-lock"></span>
                        {text text='groups_group_private_group'}
                    {else}
                        <span class="fa fa-fw fa-globe"></span>
                        {text text='groups_group_public_group'}
                    {/if}
                </div>
            </td>

            <td style="text-align: center;" class="hidden-phone">
                <a href="{jroute view='groupmembers&id='|cat:$item->id}">{$item->members}</a>
            </td>

            <td class="muted small">
                <span class="fa fa-fw fa-clock-o"></span>{date date=$item->last_activity}
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>
