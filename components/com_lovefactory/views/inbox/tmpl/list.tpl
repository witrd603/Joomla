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
            <tr class="{($item->unread) ? "unread" : ""} {("outbox" == $viewName && $approval && !$item->approved) ? "error" : ""}">
                <td>
                    <input type="checkbox" name="batch[]" value="{$item->id}"/>
                </td>

                <td>
                    <div class="username">
                        {if "outbox" == $viewName || $item->sender_id}
                            <a href="{route view='profile' user_id=$item->user_id}" title="{$item->display_name}">
                                {$item->display_name}
                            </a>
                        {else}
                            {text text='messages_system_message'}
                        {/if}
                    </div>
                </td>

                <td>
                    <a href="{route view='message' id=$item->id}">
                        {if 'inbox' === $viewName && false !== $restrictionMessage}
                            {$restrictionMessage}
                        {else}
                            {if $item->title}
                                {$item->title}
                            {else}
                                {text text='messages_no_subject'}
                            {/if}
                        {/if}
                    </a>
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
        {text text='messages_no_messages_found'}
    </div>
{/if}
