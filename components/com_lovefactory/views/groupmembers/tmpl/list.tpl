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

            <th>
                {text text='groupmembers_list_title_member'}
            </th>

            <th>
                {text text='groupmembers_list_title_since'}
            </th>
        </tr>
        </thead>

        <tbody>
        {foreach $items as $item}
            <tr>
                {if $group->isMyGroup()}
                    <td>
                        <input type="checkbox" name="batch[]" value="{$item->user_id}"/>
                    </td>
                {/if}

                <td>
                    <a href="{route view='profile' user_id=$item->user_id}">
                        {$item->display_name}
                    </a>

                    {if $item->user_id == $group->user_id}
                        <div class="small muted" style="line-height: normal;">
                            <span class="fa fa-fw fa-check-circle"></span>{text text='groupmembers_group_owner'}
                        </div>
                    {/if}

                    {if $item->banned}
                        <div class="small muted text-danger" style="line-height: normal;">
                            <span class="fa fa-fw fa-warning"></span>{text text='groupmembers_user_banned'}
                        </div>
                    {/if}
                </td>

                <td class="small muted">
                    <span class="fa fa-fw fa-clock-o"></span>{date date=$item->created_at}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{else}
    {text text='groupmembers_no_members_found'}
{/if}
