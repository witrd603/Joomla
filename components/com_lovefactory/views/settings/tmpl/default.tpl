{extends "layout.tpl"}

{block "toolbar"}
    {toolbar toolbar="profile" active="profile"}
{/block}

{block "content"}
    {form url={route controller="settings" task="save"} method="post"}
        <div class="form-horizontal">
            {foreach $form->getFieldsets() as $fieldset}
                {if $form->getFieldset($fieldset->name)}
                    <h3>{text text='settings_fieldset_'|cat:$fieldset->name}</h3>
                    {foreach $form->getFieldset($fieldset->name) as $field}
                        {$field->renderField()}
                    {/foreach}
                {/if}
            {/foreach}

            <button type="submit" class="btn btn-small btn-primary">
                <span class="fa fa-fw fa-check"></span>{text text='settings_button_submit'}
            </button>

            <div class="pull-right">
                <a href="{route view="delete"}" class="btn btn-small btn-danger">
                    <span class="fa fa-fw fa-times"></span>{text text='settings_button_delete'}
                </a>
            </div>

            {jhtml _='form.token'}
        </div>
    {/form}
{/block}
