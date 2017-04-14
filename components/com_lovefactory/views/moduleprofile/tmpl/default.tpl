{extends "layout_module.tpl"}

{block "content"}
    {if null !== $profile}
        {$renderer->render($page)}
    {/if}
{/block}
