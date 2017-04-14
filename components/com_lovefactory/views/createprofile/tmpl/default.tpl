{extends "layout.tpl"}

{block "content"}
    <form action="{jroute raw='index.php'}" method="post" enctype="multipart/form-data">
        {$renderer->render($page)}

        <div style="margin-top: 10px;">
            <button type="submit" class="btn btn-small btn-primary">
                {jtext _='createprofile_submit_form'}
            </button>

            {if $page->hasRequiredFields()}
                <span class="required">*</span>
                &nbsp;{jtext _='profile_edit_required_fields_info'}
            {/if}
        </div>

        <input type="hidden" name="option" value="com_lovefactory"/>
        <input type="hidden" name="controller" value="profile"/>
        <input type="hidden" name="task" value="create" id="task"/>
        {jhtml _='form.token'}
    </form>
{/block}
