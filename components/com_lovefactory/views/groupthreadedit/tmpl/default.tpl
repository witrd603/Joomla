{extends "layout.tpl"}

{block "heading"}
    {text sprintf="groupthreadedit_heading_title" title=$group->title}
{/block}

{block "content"}
    {form url={route controller="groupthread" task="addthread"} method="POST"}
        <div class="row-fluid form-horizontal">
            <div class="span12">
                {foreach $form->getFieldset('details') as $field}
                    {$field->renderField()}
                {/foreach}
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-small btn-primary">
                <span class="fa fa-fw fa-check"></span>{text text="groupthread_submit_form"}
            </button>

            <a href="{route view="groupthreads" id=$group->id}" class="btn btn-small">
                <span class="fa fa-fw fa-times"></span>{jtext _='groupthreadedit_cancel'}
            </a>
        </div>
        <input type="hidden" name="data[group_id]" value="{$group->id}">
    {/form}
{/block}
