{extends "layout.tpl"}

{block "heading"}
    {if $item->id}
        {text sprintf="groupedit_heading_title" title=$item->title}
    {else}
        {text text="groupedit_heading_title_add"}
    {/if}
{/block}

{block "content"}
    {form url={route controller="group" task="save" id=$item->id} method="POST"}
        <div class="row-fluid">
            <div class="span6">
                {foreach $form->getFieldset('details') as $field}
                    {$field->renderField()}
                {/foreach}
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-small btn-primary">
                <span class="fa fa-fw fa-check"></span>{text text='groupedit_submit_form'}
            </button>

            {if $item->id}
                <a href="{route view="group" id=$item->id}" class="btn btn-small">
                    <span class="fa fa-fw fa-chevron-left"></span>{text text='groupedit_back_to_group'}
                </a>
                <a href="{route controller="group" task="delete" id=$item->id}"
                   class="btn btn-small btn-danger pull-right">
                    <span class="fa fa-fw fa-times"></span>{text text='groupedit_delete_label'}
                </a>
            {/if}
        </div>
    {/form}
{/block}
