{if ($settings->enable_status && ($profile->isMyProfile() || $profile->status))}
    <div class="well well-small status-update">
        {if $profile->isMyProfile()}
            <input type="text" value="{$profile->status}" name="user-status-update" id="user-status-update"
                   maxlength="{$settings->status_max_length}"
                   placeholder="{text text="profile_update_status_placeholder"}"/>
            <div class="update-status-actions">
                <i class="factory-icon icon-loader user_status_loader" style="display: none;"></i>
                <input type="button" value="{jtext _='profile_user_status_submit_status'}"
                       class="update-status btn btn-small btn-primary">
            </div>
        {else}
            {$profile->status}
        {/if}
    </div>
{/if}
