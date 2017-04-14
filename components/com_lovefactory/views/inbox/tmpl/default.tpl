{extends "layout.tpl"}

{block "toolbar"}
    {toolbar toolbar="messages" active=$viewName}
{/block}

{block "content"}
    {form url={route view=$viewName} method="POST"}

        {include "list.tpl"}
        <div class="pagination">{$pagination->getPagesLinks()}</div>
        {include "actions.tpl"}
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="controller" value="{$viewName}"/>
    {/form}
{/block}
