{extends "layout.tpl"}

{block "content"}
    {form url={route view="invoices"}}
        <div class="filters">
            <label for="filtersort">
                {text text='list_label_sort'}
            </label>
            {$filterSort}
            {$filterOrder}
        </div>
    {if $items}
        <table class="table table-striped">
            {colgroup cols="|100|100|100|180,hidden-phone"}

            <thead>
            <tr>
                <th>{text text='invoices_list_membership'}</th>
                <th class="center">{text text='invoices_list_value'}</th>
                <th class="center">{text text='invoices_list_vat'}</th>
                <th class="center">{text text='invoices_list_total'}</th>
                <th class="center hidden-phone">{text text='invoices_list_issued_at'}</th>
            </tr>
            </thead>

            <tbody>
            {foreach $items as $item}
                <tr>
                    <td>
                        <a href="#"
                           onclick="window.open('{route view='invoice&tmpl=component' id=$item->id}', 'lovefactory-invoice', 'width=800, height=600'); return false;">{$item->membership}</a>
                    </td>

                    <td class="right">
                        {jhtml _="LoveFactory.currency" amount=$item->price currency=$item->currency}
                    </td>

                    <td class="right">
                        {jhtml _="LoveFactory.currency" amount=$item->vat_value currency=$item->currency}
                    </td>

                    <td class="right">
                        {jhtml _="LoveFactory.currency" amount=$item->total currency=$item->currency}
                    </td>

                    <td class="small muted right hidden-phone">
                        <span class="fa fa-fw fa-clock-o"></span>
                        {date date=$item->issued_at}
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {else}
        {text text='invoices_no_items_found'}
    {/if}
        <div class="pagination">{$pagination->getPagesLinks()}</div>
    {/form}
{/block}
