{extends "layout.tpl"}

{block "heading"}{/block}

{block "toolbar"}
    {toolbar toolbar="gallery" active="videos" userId=$item->user_id}
{/block}

{block "content"}
    <div class="media">
        <div class="top-info">
            {if $approvalEnabled && !$item->approved}
                <div class="text-danger pull-left">
                    <span class="fa fa-fw fa-warning"></span>{text text='video_pending_approval'}
                </div>
            {/if}

            <div class="pull-right">
                {report type='video' id=$item->id reported=$item->reported options=['style' => 'new']}
            </div>
        </div>

        <div class="media-wrapper">
            <div class="player">{$item->code}</div>
            <div class="information">
                <div class="title">{$item->title}</div>

                <div class="description">
                    <div style="overflow: hidden;" class="content">{$item->description|nl2br}</div>
                    <div class="show-more" style="display: none;">
                        <a href="#" class="btn btn-mini">
                            <span class="fa fa-fw fa-chevron-right"></span>{jtext _='video_description_show_more'}</a>
                    </div>
                </div>
            </div>
        </div>

        {if $prevId || $nextId}
            <div class="media-nav">
                {if $prevId}
                    <a href="{route view='video' id=$prevId}" class="btn btn-mini">
                        <span class="fa fa-fw fa-chevron-left"></span>{text text='video_navigation_prev'}
                    </a>
                {/if}

                {if $nextId}
                    <a class="pull-right btn btn-mini" href="{route view='video' id=$nextId}">
                        <span class="fa fa-fw fa-chevron-right"></span>{text text='video_navigation_next'}
                    </a>
                {/if}
            </div>
        {/if}

        {if !$approvalEnabled || $item->approved}
            {render controller="FrontendControllerItemComments:render" type="Video" id=$item->id}
        {/if}

    </div>
{/block}
