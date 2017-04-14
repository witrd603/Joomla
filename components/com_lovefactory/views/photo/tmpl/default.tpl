{extends "layout.tpl"}

{block "heading"}{/block}

{block "toolbar"}
    {toolbar toolbar="gallery" active="photos" userId=$item->user_id}
{/block}

{block "content"}
    <div class="media">
        <div class="top-info">
            {if $approvalEnabled && !$item->approved}
                <div class="text-danger pull-left">
                    <span class="fa fa-fw fa-warning"></span>{text text='photo_pending_approval'}
                </div>
            {/if}

            <div class="pull-right">
                {report type='photo' id=$item->id reported=$item->reported options=['style' => 'new']}
            </div>
        </div>

        <div class="media-wrapper">
            <a href="{$item->getSource()}"><img src="{$item->getSource()}"/></a>
        </div>

        {if $prevId || $nextId}
            <div class="media-nav">
                {if $prevId}
                    <a href="{route view='photo' id=$prevId}" class="btn btn-mini">
                        <span class="fa fa-fw fa-chevron-left"></span>{text text='photo_navigation_prev'}
                    </a>
                {/if}

                {if $nextId}
                    <a class="pull-right btn btn-mini" href="{route view='photo' id=$nextId}">
                        <span class="fa fa-fw fa-chevron-right"></span>{text text='photo_navigation_next'}
                    </a>
                {/if}
            </div>
        {/if}

        {if !$approvalEnabled || $item->approved}
            {render controller="FrontendControllerItemComments:render" type="Photo" id=$item->id}
        {/if}
    </div>
{/block}
