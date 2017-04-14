{extends "layout.tpl"}

{block "toolbar"}
    {toolbar toolbar="messages"}
{/block}

{block "content"}
    {form url={route controller="message" task="send"} method="post"}
        <div class="row-fluid form-horizontal">
            <div class="span12">
                {foreach $form->getFieldset('details') as $field}
                    {$field->renderField()}
                {/foreach}
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-small btn-primary">
                <span class="fa fa-fw fa-check"></span>{text text="compose_submit_message"}
            </button>
        </div>
    {/form}
{/block}
