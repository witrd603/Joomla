{extends "layout.tpl"}

{block "toolbar"}
    {toolbar toolbar="search" active=$viewName}
{/block}

{block "heading"}{/block}

{block "content"}
    {if $user->id}
        {include "save_search.tpl"}
    {/if}

    {jhtml _='LoveFactory.BeginForm' url={jroute view=$viewName}|cat:$jumpToResults}
    <div class="lovefactory-search-form" style="display: {($request) ? 'none' : 'block'}">
        {$renderer->render($page)}

        <div class="actions">
            <button type="submit" class="btn btn-small btn-primary">
                <span class="fa fa-fw fa-search"></span>{text text=$viewName|cat:'_submit_button'}
            </button>

            <button class="form-reset btn btn-small btn-link">
                <span class="fa fa-fw fa-times"></span>{text text='search_reset_form'}
            </button>
        </div>
    </div>
    {if $request}
        <a href="#" class="toggle-form btn btn-small btn-link">
            <span class="fa fa-fw fa-chevron-down"></span>{text text='search_toggle_form'}
        </a>
    {/if}

    {$viewResults}
    </form>
{/block}
