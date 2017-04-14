{form url={route view="groupthreads" id=$group->id}}
    <div class="filters">
        <label for="filtersearch">
            {text text='list_label_search'}
        </label>
        {$filterSearch}

        <label for="filtersort">
            {text text='list_label_sort'}
        </label>
        {$filterSort}
        {$filterOrder}
    </div>
{/form}
