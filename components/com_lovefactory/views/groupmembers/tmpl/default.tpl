{extends "layout.tpl"}

{block "heading"}
    {text sprintf="groupsmembers_heading_title" title=$group->title}
{/block}

{block "content"}
    {include "filters.tpl"}

    {form url={route controller="group" id=$group->id} method="POST"}
        {include "list.tpl"}

    {if $group->isMyGroup() && $items}
        <span class="batch-label">
        {text text='batch_actions_label'}
      </span>
        <button type="button" onclick="this.form.task.value='removeUsers';this.form.submit();"
                class="btn btn-small btn-danger">
            <span class="fa fa-fw fa-times"></span>{text text='group_members_remove'}
        </button>
        <button type="button" onclick="this.form.task.value='banUsers';this.form.submit();"
                class="btn btn-small btn-danger">
            <span class="fa fa-fw fa-times"></span>{text text='group_members_ban'}
        </button>
        <div class="pagination">{$pagination->getPagesLinks()}</div>
    {/if}
        <input type="hidden" name="task"/>
    {/form}
{/block}
