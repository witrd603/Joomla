{extends "layout.tpl"}

{block "toolbar"}
    {toolbar toolbar="messages"}
{/block}

{block "content"}
    {form url={route controller="interactions"} method="POST"}

        {include "list.tpl"}
        <div class="pagination">{$pagination->getPagesLinks()}</div>
        {include "actions.tpl"}
        <input type="hidden" name="task" value=""/>
    {/form}
{/block}
