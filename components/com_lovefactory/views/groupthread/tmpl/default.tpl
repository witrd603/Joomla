{extends "layout.tpl"}

{block "heading"}
    {text sprintf="groupthread_heading_title" title=$thread->title}
{/block}

{block "content"}
    {if !$thread->isApproved()}
        <div class="text-danger">
            <span class="fa fa-fw fa-warning"></span>{text text='gruopthread_pending_approval'}
        </div>
    {/if}

    {$thread->text|nl2p}
    <div>
        <div class="details-thread">
            <a href="{route view='profile' user_id=$thread->user_id}"><span
                        class="fa fa-fw fa-user"></span>{$thread->getOwnerDisplayName()}</a>
            <span class="muted">
        <span class="fa fa-fw fa-clock-o"></span>{jhtml _='LoveFactory.date' date=$thread->created_at}
      </span>

            {if !$thread->isOwner()}
                {report type='group_thread' id=$thread->id reported=$thread->reported options=['style' => 'new']}
            {/if}
        </div>

        <a href="{route view="groupthreads" id=$thread->group_id}" class="btn btn-small">
            <span class="fa fa-fw fa-chevron-left"></span>{text text='groupthread_back_to_threads'}</a>

        {if $thread->isMyGroup()}
            <a href="{route controller="groupthread" task='deletethread' id=$thread->id}"
               class="btn btn-small btn-danger">
                <span class="fa fa-fw fa-times"></span>{text text='groupthread_delete'}</a>
        {/if}
    </div>
    {include "comments.tpl"}
{/block}
