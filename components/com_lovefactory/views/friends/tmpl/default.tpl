{extends "layout.tpl"}

{block "toolbar"}
    {toolbar toolbar="profile"}
{/block}

{block "heading"}
    {if $profile->isMyProfile()}
        {text text="friends_heading_title_my_friends"}
    {else}
        {text sprintf="friends_heading_title" name=$profile->display_name}
    {/if}
{/block}

{block "content"}
    {foreach $items as $item}
        {$renderer->render($page, $item)}
        {foreachelse}
        {jtext _='friends_no_users_found'}
    {/foreach}
    <div class="pagination">
        {$pagination->getPagesLinks()}
    </div>
{/block}
