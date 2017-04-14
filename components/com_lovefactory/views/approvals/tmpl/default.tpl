{extends "layout.tpl"}

{block "toolbar"}
    {toolbar toolbar="profile" active="profile"}
{/block}

{block "heading"}
    {text text="approvals_page_heading"}
{/block}

{block "content"}
    <div class="filters">
        {jhtml _='LoveFactory.beginForm' url={jroute view=$viewName}}
        <label for="filtersort">{text text='list_label_sort'}</label>
        {$filterSort}
        {$filterOrder}
        </form>
    </div>
    {jhtml _='LoveFactory.beginForm' url={jroute _='controller=approvals'} method='POST' name='formActions'}
    <table class="table table-striped">
        {colgroup cols="20|120|50||200,hidden-phone hidden-tablet"}

        <thead>
        <tr>
            <th class="batch"><input type="checkbox" class="batch"/></th>
            <th class="list-type">{text text='approvals_list_type_title'}</th>
            <th class="list-status">{text text='approvals_list_status_title'}</th>
            <th class="list-message">{text text='approvals_list_message_title'}</th>
            <th class="list-date hidden-phone hidden-tablet">{text text='approvals_list_date_title'}</th>
        </tr>
        </thead>

        <tbody>
        {foreach $items as $item}
            <tr>
                <td>
                    <input type="checkbox" name="batch[]" value="{$item->id}"/>
                </td>

                <td>
                    {text text='approval_item_type_'|cat:$item->type}
                </td>

                <td style="text-align: center;">
                    {if $item->approved}
                        <span class="fa fa-fw fa-check text-success"></span>
                    {else}
                        <span class="fa fa-fw fa-times text-danger"></span>
                    {/if}
                </td>

                <td class="small">
                    {$item->message}
                </td>

                <td class="small muted hidden-phone hidden-tablet">
                    <span class="fa fa-fw fa-clock-o"></span>{date date=$item->created_at}
                </td>
            </tr>
            {foreachelse}
            <tr>
                <td colspan="10">
                    {text text=$viewName|cat:'_no_results_found'}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <div>
        <span class="batch-label">{text text='batch_actions_label'}</span>
        <button type="submit" class="btn btn-small btn-danger">
            <span class="fa fa-fw fa-times"></span>{text text=$viewName|cat:'_remove'}
        </button>
    </div>
    <input type="hidden" name="task" value="delete"/>
    </form>
    <div class="pagination">{$pagination->getPagesLinks()}</div>
{/block}
