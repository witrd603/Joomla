<div class="lovefactory-search-results">
    <a name="results"></a>
    <h2>{jtext plural='results_title' count=$pagination->total}</h2>

    {if isset($filter) && $items|count > 1}
        <div style="text-align: right;" class="filters">
            <label for="filter_order">
                {jtext _='vieresults_sort_by'}
            </label>
            {$filter}
            {$filterDir}
        </div>
    {/if}

    {if isset($limitedResults) && $limitedResults}
        <div class="results_limited">{jtext plural='results_limited_results' count=$limitedResults}</div>
    {/if}

    {include 'columns'|cat:$columns|cat:'.tpl'}

    <div class="pagination">
        {$pagination->getPagesLinks()}
    </div>
</div>
