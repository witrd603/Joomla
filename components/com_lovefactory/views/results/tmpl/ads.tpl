<div style="clear: both;"></div>

{foreach $ads as $ad}
    {if $ad->rows && $row % $ad->rows == 0}
        {$ad->script}
    {/if}
{/foreach}
