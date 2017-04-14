{extends "layout.tpl"}

{block "toolbar"}
    {toolbar toolbar="profile" active="profile"}
{/block}

{block "content"}
    <p>
        {text text='delete_confirm_text'}
    </p>
    <a href="{route view="profile"}" class="btn btn-small btn-primary">
        {text text='delete_button_cancel'}
    </a>
    <div class="pull-right">
        {form url={route task="profile.delete"} method="post"}
            <button type="submit" class="btn btn-small btn-danger">
                {text text='delete_button_confirm'}
            </button>
        {jhtml _='form.token'}
        {/form}
    </div>
{/block}
