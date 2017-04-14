{extends "layout.tpl"}

{block "toolbar"}
    {toolbar toolbar="friends" active={("topfriends" == $viewName) ? "topfriends" : "friends"}}
{/block}

{block "content"}
    <input type="text" id="myfriends-search" class="search-box" value="{$search}"/>
    <span style="display: inline-block;">
    <input type="checkbox" id="myfriends-online" {$online} /><label
                for="myfriends-online">{jtext _='myfriends_online_label'}</label>
  </span>
    <div id="myfriends-results">
        {include "items.tpl"}
    </div>
{/block}
