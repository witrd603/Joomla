{extends "layout.tpl"}

{block "heading"}
    {text sprintf="groupthreads_heading_title" title=$group->title}
{/block}

{block "content"}
    {include "filters.tpl"}

    {include "list.tpl"}
    <div class="pagination">{$pagination->getPagesLinks()}</div>
    <div>
        <a href="{route view='groupthreadedit' id=$group->id}" class="btn btn-small btn-success">
            {text text='group_button_create_thread'}
        </a>

        <a href="{route view='group' id=$group->id}" class="btn btn-small">
            <span class="fa fa-fw fa-chevron-left"></span>{text text='group_back_to_group'}
        </a>

        {if $approval}
            <div class="small muted pull-right">
                <span class="fa fa-fw fa-warning text-danger"></span>{text text='groupthreads_pending_approval_info'}
            </div>
        {/if}
    </div>
{/block}
