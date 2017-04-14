<div style="text-align: right;">

    {if $savedSearch}
        <a href="{$savedSearch}" class="btn btn-small btn-primary">
            {text text='search_load_search'}
        </a>
    {/if}

    {if $request}
        <form action="{jroute task='search.save'}" method="post" style="display: inline-block;">
            <button class="btn btn-small">
                {text text='search_save_search'}
            </button>

            <input type="hidden" name="search" value="{$request|json_encode|htmlentities}"/>
            <input type="hidden" name="redirect" value="{$uri->toString()|htmlentities}"/>
            <input type="hidden" name="type" value="{$viewName}"/>
        </form>
    {/if}

    {if $savedSearch}
        <form action="{jroute task='search.remove'}" method="post" style="display: inline-block;">
            <button class="btn btn-small">
                {text text='search_remove_search'}
            </button>

            <input type="hidden" name="redirect" value="{$uri->toString()|htmlentities}"/>
            <input type="hidden" name="type" value="{$viewName}"/>
        </form>
    {/if}
</div>
