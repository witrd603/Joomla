<table>
    {foreach $gateways as $gateway}
        <tr>
            <td><input type="radio" name="method" value="{$gateway->getId()}" id="method_{$gateway->getId()}"/></td>
            <td><label for="method_{$gateway->getId()}"><img src="{$gateway->getLogo()}" alt="{$gateway->getTitle()}"
                                                             style="cursor: pointer;"/></label></td>
        </tr>
    {/foreach}
</table>
