{extends "layout.tpl"}

{block "toolbar"}
    {toolbar toolbar="profile" active=$viewName}
{/block}

{block "heading"}
    {if $profile->isMyProfile()}
        {text text="page_heading_title_my_activity" name=$profile->display_name}
    {else}
        {text sprintf="page_heading_title_activity" name=$profile->display_name}
    {/if}
{/block}

{block "content"}
    <table class="table table-striped">
        <tbody>
        {foreach $items as $item}
            <tr>
                <td>
                    {$item->getTitle()}

                    <div>
                        {$item->getInfo()}
                    </div>

                    <div class="small muted actions">
                        <span class="fa fa-fw fa-clock-o"></span>{date date=$item->created_at}

                        {if $profile->isMyProfile()}
                            <a href="{jroute _='controller=activity&task=delete&id='|cat:$item->id}"
                               class="action-delete"><span
                                        class="fa fa-fw fa-times"></span>{jtext _='activity_action_delete'}</a>
                        {/if}
                    </div>
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <div class="pagination">
        {$pagination->getPagesLinks()}
    </div>
{/block}
