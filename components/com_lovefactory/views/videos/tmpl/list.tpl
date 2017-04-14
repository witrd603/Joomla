{foreach $items as $item}
    <div class="video" id="video-{$item->id}">
        {if $isMyGallery}
            <div class="shader"></div>
            <input type="checkbox" name="batch[]" value="{$item->id}"/>
            <span class="move-handle"><i class="factory-icon icon-arrow-move"></i></span>
        {/if}

        <a href="{jroute view='video&id='|cat:$item->id}" class="thumbnail"
           style="background-image: url({$item->getThumbnailSource()});"></a>

        <div class="info">
            {if $isMyGallery}
                {jhtml _='LoveFactoryVideos.privacyButton' privacy=$item->status}
            {/if}

            {if $approval && !$item->approved}
                <span class="fa fa-fw fa-warning text-danger"></span>
            {/if}

            {if $item->comments}
                <a href="{route view="video" id=$item->id}#comments" class="muted">
                    <span class="fa fa-fw fa-comment"></span>{$item->comments}</a>
            {/if}
        </div>

    </div>
    {foreachelse}
    <div class="no-items">
        {text text='videos_no_items_found'}
    </div>
{/foreach}
