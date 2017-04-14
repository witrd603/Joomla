{extends "layout.tpl"}

{block "toolbar"}
    {toolbar toolbar="gallery"}
{/block}

{block "content"}
    {form url={route view="videos"}}
    {if $isMyGallery}
        <div class="filters">

            <div style="float:left;">
                <a href="{route view="dialog&format=raw&layout=videoadd"}" class="video-add btn btn-small btn-primary">
                    <span class="fa fa-fw fa-plus"></span>{text text='videos_add_video'}</a>
            </div>

            {$filterPrivacy}
        </div>
    {/if}
        <div class="privacy-status alert alert-error" style="display: none;"></div>
        <div class="videos" style="float: left; clear: both;">
            {include "list.tpl"}
        </div>
        <div style="clear: both;"></div>
    {if $isMyGallery}
        {if $approval}
            <div class="small muted">
                <span class="fa fa-fw fa-warning text-danger"></span>{text text='videos_pending_approval_info'}
            </div>
        {/if}
        <div class="actions">
            {if $items}
                <div class="check-all-container" style="margin: 10px 0;">{jhtml _='LoveFactory.checkAll'}</div>
                <span class="batch-label">{text text='batch_actions_label'}</span>
                <button type="button" class="batch-delete btn btn-small btn-danger">
                    <span class="fa fa-fw fa-times"></span>{text text='batch_actions_delete'}
                </button>
                <span class="batch-label"
                      style="margin-right: 5px;">{text text='videos_batch_change_privacy'}</span>{jhtml _='LoveFactoryVideos.privacyButton'}
            {/if}
        </div>
    {/if}

    {/form}
{/block}
