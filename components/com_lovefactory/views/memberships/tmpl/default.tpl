{extends "layout.tpl"}

{block "content"}
    <table class="memberships">
        <thead>
        <tr>
            <td></td>
            {foreach $items as $item}
                <th>{$item->title}</th>
            {/foreach}
        </tr>
        </thead>

        <tbody>
        {foreach $features as $feature => $key}
            <tr class="{cycle values=",alternate"}">
                <th>
                    <i class="factory-icon icon-membership-feature-{$feature}"></i>
                    {jtext _="memberships_membership_features_"|cat:$feature}
                </th>
                {foreach $items as $item}
                    <td>
                        {if isset($item->features[$feature])}
                            {$item->features[$feature]}
                        {/if}
                    </td>
                {/foreach}
            </tr>
        {/foreach}
        </tbody>
    </table>
    <h1>{jtext _='memberships_prices_title'}</h1>
    <table class="memberships prices">
        <thead>
        <tr>
            <td></td>
            {foreach $items as $item}
                <th>{$item->title}</th>
            {/foreach}
        </tr>
        </thead>

        <tbody>
        {foreach $prices as $months => $pricing}
            <tr class="{cycle values=",alternate"}">
                <th>{jtext plural='memberships_price_months' count=$months}</th>

                {foreach $items as $item}
                    <td class="{(isset($pricing[$item->id])) ? 'right' : ''}">
                        {if isset($pricing[$item->id])}
                            {if !is_array($pricing[$item->id])}

                                {if '0.00' ==$pricing[$item->id]->amount}
                                    <a href="{jroute task='membership.free&id='|cat:$pricing[$item->id]->id}">{$pricing[$item->id]->price}</a>
                                {else}
                                    <a href="{jroute view='membershipbuy&id='|cat:$pricing[$item->id]->id}">{$pricing[$item->id]->price}</a>
                                {/if}

                            {else}

                                {foreach $pricing[$item->id] as $id => $prices}
                                    {foreach $prices as $price}
                                        {if '0.00' == $price.price}
                                            <a class="gender"
                                               href="{jroute task='membership.free&id='|cat:$id}">{$price.label}</a>
                                        {else}
                                            <a class="gender"
                                               href="{jroute view='membershipbuy&id='|cat:$id}">{$price.label}</a>
                                        {/if}
                                    {/foreach}
                                {/foreach}

                            {/if}
                        {/if}
                    </td>
                {/foreach}
            </tr>
        {/foreach}
        </tbody>
    </table>
    {if $trials}
        <h1>{jtext _='memberships_trials_title'}</h1>
        <table class="memberships prices">
            <thead>
            <tr>
                <td></td>
                {foreach $items as $item}
                    <th>{$item->title}</th>
                {/foreach}
            </tr>
            </thead>

            <tbody>
            {foreach $trials as $hours => $trial}
                <tr class="{cycle values=",alternate"}">
                    <th>{jtext plural='memberships_trials_hours' count=$hours}</th>

                    {foreach $items as $item}
                        <td>
                            {if isset($trial[$item->id])}
                                <a href="{jroute task='membership.trial&id='|cat:$trial[$item->id]->id}">{jtext _='memberships_trials_free_trial'}</a>
                            {/if}
                        </td>
                    {/foreach}
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/if}
{/block}
