{jhtml _="script" file="components/com_lovefactory/assets/js/views/signup.js"}

<form action="{jroute raw='index.php'}" method="post" enctype="multipart/form-data">
    {$renderer->render($page)}

    <div style="margin-top: 10px;">
        <button type="submit" class="btn btn-small btn-primary">
            <span class="fa fa-fw fa-check"></span>
            {if $settings->registration_membership}
                {jtext _='profile_signup_submit_form_and_membership'}
            {else}
                {jtext _='profile_signup_submit_form'}
            {/if}

        </button>

        {if $page->hasRequiredFields()}
            <span class="required">*</span>
            &nbsp;{jtext _='profile_edit_required_fields_info'}
        {/if}
    </div>

    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="controller" value="signup"/>
    <input type="hidden" name="task" value="signup" id="task"/>
    {jhtml _='form.token'}
</form>
