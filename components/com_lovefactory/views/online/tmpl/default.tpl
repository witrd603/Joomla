{extends "layout.tpl"}

{block "content"}
    {form url={route view="online"}}
    {$viewResults}
    {/form}
{/block}
