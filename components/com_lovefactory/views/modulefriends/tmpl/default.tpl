{extends "layout_module.tpl"}

{block "content"}
    <input type="text" id="myfriends-search" class="search-box" value="{$search}" style="margin: 0;"/>
    <span style="display: inline-block; margin-bottom: 10px;">
    <input type="checkbox" id="myfriends-online" {$online} style="margin: 0;"/>
    <label for="myfriends-online" style="display: inline-block;">{jtext _='myfriends_online_label'}</label>
  </span>
    <div id="myfriends-results">
        {include "items.tpl"}
    </div>
{/block}
