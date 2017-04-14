{extends "layout.tpl"}

{block "content"}
    <h3>{text text='membershipbuy_fieldset_legend'}</h3>
    <form action="{jroute task='gateway.process'}" method="post" id="paymentForm" class="form-horizontal">
        <div class="control-group">
            <label for="price" class="control-label">{text text='membershipbuy_price_label'}</label>

            <div class="controls">
                {$priceSelect}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label">{text text='membershipbuy_gateway_label'}</label>

            <div class="controls">
                {include 'gateways.tpl'}
            </div>
        </div>

        <button type="submit" class="btn btn-small btn-primary">
            {text text='membershipbuy_form_submit'}
        </button>

        <input type="hidden" name="step" value="1"/>
    </form>
{/block}
