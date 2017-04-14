{foreach $items as $item}
    <div class="photo" id="photo-{$item->id}">
        {if $isMyGallery}
            <div class="shader"></div>
            <input type="checkbox" name="batch[]" value="{$item->id}"/>
            <span class="move-handle"><i class="factory-icon icon-arrow-move"></i></span>
            {if $item->id == $user->main_photo}
                <i class="factory-icon icon-star profile-photo"></i>
            {/if}
        {/if}

        <a href="{jroute view='photo&id='|cat:$item->id}" class="thumbnail"
           style="background-image: url({$item->getSource(true)});"></a>

        <div class="info">
            {if $isMyGallery}
                {jhtml _='LoveFactoryPhotos.privacyButton' privacy=$item->status}
            {/if}

            {if $approval && !$item->approved}
                <span class="fa fa-fw fa-warning text-danger"></span>
            {/if}

            {if $item->comments}
                <a href="{route view="photo" id=$item->id}#comments" class="muted">
                    <span class="fa fa-fw fa-comment"></span>{$item->comments}</a>
            {/if}
        </div>

    </div>
    {foreachelse}
    <div class="no-items">
        {jtext _='photos_no_items_found'}
    </div>
{/foreach}
