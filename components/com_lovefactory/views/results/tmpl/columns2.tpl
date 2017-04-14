{for $i = 0 to $items|count - 1 step 2}
    <div class="row-fluid">
        <div class="span6 result">
            {$rendererResults->render($pageResults, $items[$i])}
        </div>

        {if isset($items[$i + 1])}
            <div class="span6 result">
                {$rendererResults->render($pageResults, $items[$i + 1])}
            </div>
        {/if}
    </div>
    {include 'ads.tpl' row=$i / 2 + 1}
{/for}
