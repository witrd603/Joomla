<div class="item-comments">
    <h3>
        {text text=$viewName|cat:"_fieldset_your_comment"}
    </h3>

    {if $userId}
        <textarea></textarea>
        <div class="buttons">
            <button type="button" id="{$itemType}-{$itemId}" class="btn btn-small btn-primary"
                    data-url="{jroute task="itemcomment.add"}">
                <span class="fa fa-fw fa-refresh fa-spin"
                      style="display: none;"></span>{text text=$viewName|cat:'_submit_button'}
            </button>
        </div>
    {else}
        {jhtml _="LoveFactory.LoginLink" message={jtext _="profile_login_to_interact"}}
    {/if}

    <h3>
        {text text=$viewName|cat:"_fieldset_other_comments"}
    </h3>

    {include "comments.tpl"}
</div>
