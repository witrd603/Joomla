<div class="well well-small ratings">
    <h3>{jtext _='profile_ratings_fieldset_title'}</h3>

    <!-- Rate profile -->
    {if !$profile->isMyProfile() && (!$ratings->myRating || $ratings->allowUpdate)}
        <div class="profile-vote">
            {if (0 == $visitor->status)}
                {include 'default_login.tpl'}
            {elseif 1 == $visitor->status}
                {include 'default_fillin.tpl'}
            {else}
                <div><b>{jtext _='profile_ratings_rate_profile'}</b></div>
                <i class="factory-icon icon-loader stars-loader" style="display: none;"></i>
                <div class="rating-stars">
                    {jhtml _='LoveFactory.rating' userId=$profile->user_id}
                </div>
            {/if}
        </div>
    {/if}

    <!-- Profile rating -->
    <div class="profile-rating">
        <b>{$profile->rating}</b><br/>{jtext plural='profile_ratings_current_votes' count=$profile->votes}
    </div>

    <!-- My rating -->
    {if !$profile->isMyProfile() && $ratings->myRating}
        <div class="my-rating">
            <b>{$ratings->myRating}</b><br/>{jtext _='profile_ratings_my_rating'}
        </div>
    {/if}

    <!-- Latest votes -->
    {if $ratings->latestRatings}
        <div class="latest-votes">
            <div><b>{jtext _='profile_ratings_latest_votes'}</b></div>

            <ul>
                {foreach $ratings->latestRatings as $rating}
                    <li>
                        <div class="vote"><i class="factory-icon icon-star"></i>{$rating->rating}</div>
                        {if $rating->valid_user}
                            <a href="{jroute view='profile&user_id='|cat:$rating->sender_id}"><i
                                        class="factory-icon icon-user"></i>{$rating->display_name}</a>
                        {else}
                            <span><i class="factory-icon icon-user"></i>{jtext _='profile_rating_user_removed'}</span>
                        {/if}
                    </li>
                {/foreach}
            </ul>
        </div>
    {/if}
</div>
