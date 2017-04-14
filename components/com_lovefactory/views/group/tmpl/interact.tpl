<h3>{text text='group_fieldset_interact'}</h3>

<ul>
    <li><a href="{jroute view='groupthreads&id='|cat:$item->id}"><i
                    class="factory-icon icon-balloon"></i>{text text='group_interact_threads'}</a></li>
    <li><a href="{jroute view='groupmembers&id='|cat:$item->id}"><i
                    class="factory-icon icon-users"></i>{text text='group_interact_members'}</a></li>
    {if $isOwner}
        <li><a href="{jroute view='groupedit&id='|cat:$item->id}"><i
                        class="factory-icon icon-pencil"></i>{text text='group_interact_update'}</a></li>
        <li><a href="{jroute view='groupbanned&id='|cat:$item->id}"><i
                        class="factory-icon icon-cross-circle"></i>{text text='group_interact_banned'}</a></li>
    {else}
        {if $isMember}
            <li><a href="{jroute task='group.leave&id='|cat:$item->id}"><i
                            class="factory-icon icon-user-minus"></i>{text text='group_leave_group'}</a></li>
        {else}
            <li><a href="{jroute task='group.join&id='|cat:$item->id}"><i
                            class="factory-icon icon-user-plus"></i>{text text='group_join_group'}</a></li>
        {/if}
    {/if}

    {if !$isOwner}
        {jhtml _='LoveFactory.reportButton' type='group' id=$item->id reported=$item->reported}
    {/if}
</ul>
