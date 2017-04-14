{extends "layout.tpl"}

{block "toolbar"}
    {toolbar toolbar="friends"}
{/block}

{block "heading"}
    {text text="blocked_page_heading"}
{/block}

{block "content"}
    {jhtml _='LoveFactory.beginForm' url={jroute _='controller=blacklist'} method='POST' name='formBlacklist'}
    <table class="table table-striped">
        {colgroup cols="20||170"}

        <thead>
        <tr>
            <th class="batch"><input type="checkbox" class="batch"/></th>
            <th>{text text="blacklist_heading_username"}</th>
            <th>{text text="blacklist_heading_date"}</th>
        </tr>
        </thead>

        <tbody>
        {foreach $items as $item}
            <tr>
                <td class="center">
                    <input type="checkbox" name="batch[]" value="{$item->user_id}"/>
                </td>

                <td>
                    <a href="{jroute view='profile&user_id='|cat:$item->user_id}"><i
                                class="factory-icon icon-user"></i>{$item->display_name}</a>
                </td>

                <td class="small muted">
                    <span class="fa fa-fw fa-clock-o"></span>{jhtml _='LoveFactory.date' date=$item->date}
                </td>

            </tr>
            {foreachelse}
            <tr>
                <td colspan="10">
                    {text text='blocked_no_users_found'}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <div>
        <span class="batch-label">{text text='messages_label_batch'}</span>
        <button type="submit" class="btn btn-small btn-danger"
                onclick="this.form.task.value='delete'; this.form.submit();">
            <span class="fa fa-fw fa-times"></span>{text text='blocked_button_delete'}
        </button>
    </div>
    <input type="hidden" name="task" value=""/>
    </form>
    <div class="pagination">{$pagination->getPagesLinks()}</div>
{/block}
