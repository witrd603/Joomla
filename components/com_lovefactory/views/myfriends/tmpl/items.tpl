{foreach $items as $item}
    {$renderer->render($page, $item)}
    {foreachelse}
    <div>
        {text text="myfriends_no_friends_found"}
    </div>
{/foreach}

{strip}
    <div class="pagination">
        {$pagination->getPagesLinks()}
    </div>
{/strip}
