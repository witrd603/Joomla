{if $items}
    <table class="table table-striped">
        {colgroup cols="|60|180,hidden-phone"}

        <thead>
        <tr>
            <th></th>
            <th class="center">{text text='group_list_title_posts'}</th>
            <th class="hidden-phone">{text text='group_list_title_last_activity'}</th>
        </tr>
        </thead>

        <tbody>
        {foreach $items as $item}
            <tr>
                <td>
                    <a href="{jroute view='groupthread&id='|cat:$item->id}">
                        <b>{$item->title}</b>
                    </a>

                    {if $approval && !$item->approved}
                        <div class="small muted">
                            <span class="fa fa-fw fa-warning text-danger"></span>Pending approval
                        </div>
                    {/if}
                </td>

                <td class="center">
                    {$item->posts}
                </td>

                <td class="small muted hidden-phone">
                    <span class="fa fa-fw fa-clock-o"></span>{date date=$item->last_activity}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{else}
    {text text='group_threads_not_found'}
{/if}
