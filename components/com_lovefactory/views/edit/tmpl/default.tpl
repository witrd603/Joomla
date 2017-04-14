{extends "layout.tpl"}

{block "toolbar"}
    {toolbar toolbar="profile" active="profile"}
{/block}

{block "content"}
    <form action="{jroute raw='index.php'}" method="post" enctype="multipart/form-data">
        {$renderer->render($page)}

        <div class="actions">
            {if $settings->approval_profile && $profile->isDraft}
                <button type="submit" onclick="document.getElementById('task').value = 'restore';"
                        class="btn btn-small btn-danger">
                    <span class="fa fa-fw fa-times"></span>{text text='profile_edit_approval_restore_changes'}
                </button>
            {/if}

            {if $settings->approval_profile && $profile->isDraft && !$profile->isPending}
                <button type="submit" onclick="document.getElementById('task').value = 'submitapproval';"
                        class="btn btn-small btn-success">
                    <span class="fa fa-fw fa-check"></span>{text text='profile_edit_approval_submit'}
                </button>
            {/if}

            {if !$settings->approval_profile || !$profile->isDraft || !$profile->isPending}
                <button type="submit" class="btn btn-small btn-primary">
                    <span class="fa fa-fw fa-check"></span>{text text='profile_edit_submit_form'}
                </button>
                {if $page->hasRequiredFields()}
                    <div style="display: inline-block;">
                        <span class="required">*</span>&nbsp;{jtext _='profile_edit_required_fields_info'}
                    </div>
                {/if}
            {/if}
        </div>

        <input type="hidden" name="option" value="com_lovefactory"/>
        <input type="hidden" name="controller" value="profile"/>
        <input type="hidden" name="task" value="update" id="task"/>
        {jhtml _='form.token'}
    </form>
{/block}
