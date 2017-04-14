{for $i = 0 to $items|count - 1}
    <div class="result">
        {$rendererResults->render($pageResults, $items[$i])}
    </div>
    {include 'ads.tpl' row=$i + 1}
{/for}
