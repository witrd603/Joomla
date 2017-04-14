{extends "layout.tpl"}

{block "toolbar"}
    {toolbar toolbar="friends" active="relationship"}
{/block}

{block "content"}
    {if $item}
        {$renderer->render($page, $item)}
    {else}
        {text text="myrelationship_no_relation_found"}
    {/if}
{/block}
