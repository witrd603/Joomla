{extends "layout.tpl"}

{block "heading"}{/block}

{block "toolbar"}
    {toolbar toolbar="profile"}
{/block}

{block "content"}
    <div class="row-fluid">
        <div class="span8">
            {if $profile->isMyProfile() && $settings->profile_status_change && in_array($profile->online, array(1, 2))}
                <i class="factory-icon icon-exclamation-red"></i>
                {if 1 == $profile->online}{jtext _='profile_status_friends_only'}{else}{jtext _='profile_status_offline'}{/if}
            {/if}

            {$renderer->render($page)}
        </div>

        <div class="span4">
            {if $settings->enable_status}
                {include 'default_user_status.tpl'}
            {/if}

            {include 'default_interact.tpl'}

            {if $settings->enable_rating}
                {include 'default_ratings.tpl'}
            {/if}
        </div>
    </div>
{/block}
