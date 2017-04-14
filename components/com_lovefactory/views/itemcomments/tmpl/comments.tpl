<div class="comments-wrapper" data-url="{jroute view="itemcomments&format=raw&layout=comments&limitstart=0"}">
    {if $items}
        <ul class="media-list">
            {foreach $items as $item}
                <li class="media clearfix {if $approval && !$item->approved}pending-approval{/if}">
                    <a class="pull-left hidden-phone" href="{jroute view="profile&user_id="|cat:$item->user_id}">
                        <img class="media-object" src="{$item->thumbnail}"/>
                    </a>

                    <div class="media-body">
                        <a href="{route view="profile" user_id=$item->user_id}" class="username">
                            <i class="factory-icon icon-user"></i>{$item->display_name}
                        </a>

                        {if $item->isMyItem && !$item->read}
                            <span class="label label-success">{jtext _='itemcomment_new'}</span>
                        {/if}

                        {if $approval && !$item->approved}
                            <div class="text-danger pending-warning">
                                <span class="fa fa-fw fa-warning"></span>{jtext _='approvals_item_pending_approval'}
                            </div>
                        {/if}

                        <div class="message-content">
                            {$item->message|nl2p}
                        </div>

                        <div class="small muted message-info">
                            <span class="fa fa-fw fa-clock-o"></span>{date date=$item->created_at}

                            {if $item->isMyComment || $item->isMyItem}
                                <a href="{route controller="itemcomment" task="delete" id=$item->id}"
                                   class="comment-delete">
                                    <span class="fa fa-fw fa-times"></span>{text text="itemcomment_button_delete"}</a>
                            {/if}

                            {if $userId != $item->user_id}
                                {report type="item_comment."|cat:$item->item_type id=$item->id reported=$item->reported options=['style' => 'new']}
                            {/if}
                        </div>
                    </div>
                </li>
            {/foreach}
        </ul>
        <div class="pagination">
            {$pagination->getPagesLinks()}
        </div>
    {else}
        {text text=$viewName|cat:"_no_comments_found"}
    {/if}
</div>
