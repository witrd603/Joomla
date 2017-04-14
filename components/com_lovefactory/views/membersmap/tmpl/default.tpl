{extends "layout.tpl"}

{block "content"}
    <div>
        {$map->renderMap('lovefactory-membersmap', $location)}
    </div>
{/block}
