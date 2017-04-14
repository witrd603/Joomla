{extends "layout.tpl"}

{block "toolbar"}
    {toolbar toolbar="gallery"}
{/block}

{block "content"}
    {form url={route view="photos"}}

    {if $isMyGallery}
        <div class="filters">
            <div style="float:left;">
                <a href="{route view="dialog&format=raw&layout=photoupload"}{$test}"
                   class="photos-upload btn btn-small btn-primary">
                    <span class="fa fa-fw fa-plus"></span>{text text='photos_add_photos'}
                </a>

                {if $gravatar}
                    <a href="{route controller="photo" task="addgravatar"}" class="btn btn-small">
                        <span class="fa fa-fw fa-plus"></span>{text text='photos_add_photo_gravatar'}
                    </a>
                {/if}
            </div>

            {$filterPrivacy}
        </div>
    {/if}
        <ul class="upload-status" style="display: none;"></ul>
        <div class="privacy-status alert alert-error" style="display: none;"></div>
        <div class="photos" style="float: left; clear: both;">
            {include 'photos.tpl'}
        </div>
        <div style="clear: both;"></div>
    {if $isMyGallery}
        {if $approval}
            <div class="small muted">
                <span class="fa fa-fw fa-warning text-danger"></span>{text text='photos_pending_approval_info'}
            </div>
        {/if}
        <div class="check-all-container"
             style="margin: 10px 0; display: {($items) ? 'block' : 'none'}">{jhtml _='LoveFactory.checkAll'}</div>
        <div class="actions" style="display: {($items) ? 'block' : 'none'}">
            <span class="batch-label hidden-phone">{text text='batch_actions_label'}</span>

            <button type="button" class="batch-delete btn btn-small btn-danger">
                <span class="fa fa-fw fa-times"></span>{text text='batch_actions_delete'}
            </button>

            <button type="button" class="photo-set-main btn btn-small">
                <span class="fa fa-fw fa-user"></span>{text text='photos_actions_button_set_profile_photo'}
            </button>

            <span class="batch-label hidden-phone"
                  style="margin-right: 5px;">{text text='videos_batch_change_privacy'}</span>{jhtml _='LoveFactoryPhotos.privacyButton'}
        </div>
    {/if}

    {/form}
{/block}
