{extends "layout.tpl"}

{block "content"}
    {form url={jroute view="groups"}}
        {include "filters.tpl"}

    {if $items}
        {include "groups.tpl"}
        <div class="pagination">{$pagination->getPagesLinks()}</div>
    {else}
        {text text="groups_no_items_found"}
    {/if}
        <a href="{jroute view="groupedit"}" class="btn btn-small btn-success">
            {text text='groups_button_create_group'}
        </a>
    {/form}
{/block}
