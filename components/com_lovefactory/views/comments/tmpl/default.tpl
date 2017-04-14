{extends "layout.tpl"}

{block "toolbar"}
    {toolbar toolbar="profile"}
{/block}

{block "heading"}
    {if $profile->isMyProfile()}
        {text text="comments_heading_title_my_profile"}
    {else}
        {text sprintf="comments_heading_title" name=$profile->display_name}
    {/if}
{/block}

{block "content"}
    {render controller="FrontendControllerItemComments:render" type="profile" id=$profile->user_id}
{/block}
