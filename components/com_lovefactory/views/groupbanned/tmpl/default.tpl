{extends "layout.tpl"}

{block "heading"}
    {text sprintf="groupbanned_heading_title" title=$group->title}
{/block}

{block "content"}
    {include "filters.tpl"}

    {form url={route controller="group"} method="POST"}
        {include "list.tpl"}
        <div class="pagination">{$pagination->getPagesLinks()}</div>
    {if $group->isMyGroup() && $items}
        <div>
            <span class="batch-label">{text text='batch_actions_label'}</span>
            <button type="submit" class="btn btn-small btn-success">
                <span class="fa fa-fw fa-check"></span>{text text='groupbanned_remove'}
            </button>
        </div>
    {/if}
        <input type="hidden" name="task" value="removebanned"/>
        <input type="hidden" name="id" value="{$group->id}"/>
    {/form}
{/block}
