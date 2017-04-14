{extends "layout.tpl"}

{block "heading"}
    {text sprintf="page_heading_title_group" title=$item->title}
{/block}

{block "content"}
    <div class="row-fluid">
        <div class="span8">
            <h3>{text text='group_fieldset_description'}</h3>

            {if $item->description}
                {$item->description|nl2p}
            {else}
                {text text='group_no_description'}
            {/if}

            <a href="{jroute view='groups'}" class="btn btn-small btn-primary">
                <span class="fa fa-fw fa-chevron-left"></span>{text text='group_button_back_to_groups'}
            </a>
        </div>

        <div class="span4">
            {if !$item->isApproved()}
                <div class="alert alert-danger">
                    <span class="fa fa-fw fa-warning"></span>{text text='group_pending_approval'}
                </div>
            {/if}

            <div class="well well-small">
                {include "interact.tpl"}
            </div>

            <div class="well well-small">
                {include "information.tpl"}
            </div>
        </div>
    </div>
{/block}
