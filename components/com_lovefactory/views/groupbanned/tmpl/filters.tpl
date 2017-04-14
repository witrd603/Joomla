{form url={route view="groupbanned" id=$group->id}}
    <div class="filters">
        <div class="pull-left">
            <a href="{route view="group" id=$group->id}" class="btn btn-small btn-primary">
                <span class="fa fa-fw fa-chevron-left"></span>{text text='group_back_to_group'}
            </a>
        </div>

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
