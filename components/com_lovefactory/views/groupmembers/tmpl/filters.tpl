{form url={route view="groupmembers" id=$group->id}}
    <div class="filters">

        <a href="{route view='group' id=$group->id}" class="btn btn-small btn-primary pull-left">
            <span class="fa fa-fw fa-chevron-left"></span>{text text='group_back_to_group'}
        </a>

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
