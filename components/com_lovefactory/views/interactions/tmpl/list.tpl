{if $items}
    <table class="table table-striped">
        {colgroup cols="30|150||180,hidden-phone"}

        <thead>
        <tr>
            <th>
                <input type="checkbox" class="batch"/>
            </th>
            <th></th>
            <th></th>
            <th class="hidden-phone"></th>
        </tr>
        </thead>

        <tbody>
        {foreach $items as $item}
            <tr class="{('received' == $item->status && !$item->seen) ? 'unread' : ''}">
                <td>
                    <input type="checkbox" name="batch[]" value="{$item->id}"/>
                </td>

                <td>
                    <a href="{route view='profile&' user_id=$item->user_id}">
                        {$item->display_name}
                    </a>
                </td>

                <td>
                    {if "received" == $item->status}
                        <span class="fa fa-fw fa-arrow-left text-success"></span>
                    {else}
                        <span class="fa fa-fw fa-arrow-right text-danger"></span>
                    {/if}
                    {text plural='interactions_interaction_'|cat:$item->status count=$item->type_id}

                    {if "received" == $item->status && $item->type_id % 2 != 0}
                        {if !$item->responded}
                            <a href="{route controller="interaction" task="respond" id=$item->id}">
                                <span class="fa fa-fw fa-arrow-right text-danger"></span>{text text='interactions_interaction_respond'}
                            </a>
                        {else}
                            <span class="small muted">
                {text text='interations_interation_responded'}
              </span>
                        {/if}
                    {/if}
                </td>

                <td class="small muted right hidden-phone">
                    <span class="fa fa-fw fa-clock-o"></span>{date date=$item->date}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{else}
    <div class="no-results">
        {text text="interactions_no_interactions_found"}
    </div>
{/if}
