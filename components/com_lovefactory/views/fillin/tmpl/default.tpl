{extends "layout.tpl"}

{block "content"}
    <form action="{jroute raw='index.php'}" method="post" enctype="multipart/form-data">
        {$renderer->render($page)}

        <div class="actions">
            <button type="submit" class="btn btn-small btn-primary">
                <span class="fa fa-fw fa-check"></span>{text text='profile_fillin_submit_form'}
            </button>

            {if $page->hasRequiredFields()}
                <span class="required">*</span>
                &nbsp;{jtext _='profile_edit_required_fields_info'}
            {/if}
        </div>

        <input type="hidden" name="option" value="com_lovefactory"/>
        <input type="hidden" name="controller" value="fillin"/>
        <input type="hidden" name="task" value="fillin" id="task"/>
        {jhtml _='form.token'}
    </form>
{/block}
