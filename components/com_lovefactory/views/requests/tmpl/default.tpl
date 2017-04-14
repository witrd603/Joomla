{extends "layout.tpl"}

{block "toolbar"}
    {toolbar toolbar="friends"}
{/block}

{block "content"}
    {foreach $items as $item}
        {$renderer->render($page, $item)}
        {foreachelse}
        {jtext _='requests_no_requests_found'}
    {/foreach}
{/block}

