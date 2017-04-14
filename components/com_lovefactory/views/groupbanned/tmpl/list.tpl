{if $items}
    <table class="table table-striped">
        <colgroup>
            {if $group->isMyGroup()}
                <col style="width: 20px;"/>
            {/if}
            <col/>
            <col style="width: 180px;"/>
        </colgroup>

        <thead>
        <tr>
            {if $group->isMyGroup()}
                <th class="batch">
                    <input type="checkbox" class="batch"/>
                </th>
            {/if}
            <th></th>
            <th>
                {text text='groupmembers_list_title_since'}
            </th>
        </tr>
        </thead>

        <tbody>
        {foreach $items as $item}
            <tr class="{cycle values=',alternate'}">
                {if $group->isMyGroup()}
                    <td>
                        <input type="checkbox" name="batch[]" value="{$item->user_id}"/>
                    </td>
                {/if}

                <td>
                    <a href="{route view="profile" user_id=$item->user_id}">
                        {$item->display_name}
                    </a>
                </td>

                <td class="small muted">
                    <span class="fa fa-fw fa-clock-o"></span>{date date=$item->created_at}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{else}
    {text text="groupbanned_no_results_found"}
{/if}
