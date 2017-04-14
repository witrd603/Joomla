<li class="comment {cycle values='alternate,'}" id="comment-{$item->id}">
    <img src="{$item->thumbnail}"/>

    <div class="message">
        {if $approval && !$item->approved}
            <div class="pending-approval"><i
                        class="factory-icon icon-exclamation-red"></i>{text text='grouppost_pending_approval'}</div>
        {/if}

        <a href="{route view="profile" user_id=$item->user_id}" style="font-weight: bold;">
            <i class="factory-icon icon-user"></i>{$item->display_name}
        </a>

        <div style="margin-top: 9px;">
            {$item->text|nl2p}
        </div>

        <div class="info small muted">
            <span class="fa fa-fw fa-clock-o"></span>{date date=$item->created_at}

            <div class="right">
                {jhtml _='LoveFactory.reportButton' type='group_post' id=$item->id reported=$item->reported options=['showIcon' => false]}

                {if $item->isMyComment || $thread->isMyGroup()}
                    &nbsp;
                    <a href="#" class="comment-delete">{text text='itemcomment_button_delete'}</a>
                {/if}

                {if $thread->isMyGroup()}
                    {if $item->is_banned}
                        &nbsp;{text text='group_banned_user'}
                    {else}
                        &nbsp;
                        <a href="{jroute task='groupban.ban&user_id='|cat:$item->user_id|cat:'&group_id='|cat:$item->group_id}"
                           class="user-comment-ban">{text text='group_ban_user'}</a>
                    {/if}
                {/if}
            </div>
        </div>
    </div>
</li>
