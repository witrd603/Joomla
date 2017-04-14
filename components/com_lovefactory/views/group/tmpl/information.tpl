<h3>{text text='group_fieldset_information'}</h3>

<ul>
    <li>{($item->private) ? {text text='group_group_prvate'} : {text text='group_group_public'}}</li>
    <li>{text text='group_info_owner'}: <a href="{jroute view='profile&user_id='|cat:$item->user_id}"><i
                    class="factory-icon icon-user"></i>{$owner}</a></li>
    <li>{text text='group_info_members'}: {$members}</li>
    <li>{text text='group_info_threads'}: {$threads}</li>
    <li>{text text='group_info_posts'}: {$posts}</li>
</ul>
