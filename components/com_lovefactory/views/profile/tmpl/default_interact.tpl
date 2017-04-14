<div class="well well-small">
    <h3>
        {if $profile->isMyProfile()}
            {jtext _='profile_fieldset_profile_actions'}
        {else}
            {jtext _='profile_fieldset_interact'}
        {/if}
    </h3>

    {if $profile->isMyProfile()}
        <ul>
            <li><a href="{jroute view='edit'}"><i
                            class="factory-icon icon-user-pencil"></i>{jtext _='profile_edit_profile'}</a></li>
            <li><a href="{jroute view='settings'}"><i
                            class="factory-icon icon-gear"></i>{jtext _='profile_edit_settings'}</a></li>
            <li><a href="{jroute view='mymembership'}"><i
                            class="factory-icon icon-medal"></i>{jtext _='profile_my_membership'}</a></li>
        </ul>
    {else}
        {if 0 == $visitor->status}
            {include 'default_login.tpl'}
        {elseif 1 == $visitor->status}
            {include 'default_fillin.tpl'}
        {else}
            <ul>
                <!-- Send a quick message -->
                <li>{jhtml _='LoveFactory.QuickMessage' userId=$profile->user_id}</li>

                <!-- Friendship button -->
                <li>{jhtml _='LoveFactory.FriendshipButton' userId=$profile->user_id}</li>

                <!-- Relationship button -->
                <li>{jhtml _='LoveFactory.RelationshipButton' userId=$profile->user_id}</li>

                <!-- Write a message -->
                {if ($settings->enable_messages)}
                    <li><a href="{jroute view='compose&receiver='|cat:$profile->user_id}"><i
                                    class="factory-icon icon-mail-pencil"></i>{jtext _='profile_interact_write_message'}
                        </a></li>
                {/if}

                <!-- Send Interaction Wink -->
                <li>{jhtml _='LoveFactory.InteractionButton' userId=$profile->user_id interaction='wink'}</li>

                <!-- Send Interaction Kiss -->
                <li>{jhtml _='LoveFactory.InteractionButton' userId=$profile->user_id interaction='kiss'}</li>

                <!-- Send Interaction Hug -->
                <li>{jhtml _='LoveFactory.InteractionButton' userId=$profile->user_id interaction='hug'}</li>

                <li>
                    <i class="factory-icon icon-image"></i><a
                            href="{jroute view="photos&user_id="|cat:$profile->user_id}">{jtext _="profile_interact_gallery"}</a>
                </li>

                <!-- Block -->
                <li>{jhtml _='LoveFactory.BlockButton' userId=$profile->user_id isBlocked=$profile->blocked}</li>

                <!-- Report -->
                <li>{jhtml _='LoveFactory.reportButton' type='profile' id=$profile->user_id reported=false}</li>
            </ul>
        {/if}
    {/if}
</div>

