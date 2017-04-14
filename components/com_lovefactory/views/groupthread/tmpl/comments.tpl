<h3>{text text='groupthread_fieldset_your_message'}</h3>

<form action="{jroute task='groupthread.addpost&id='|cat:$thread->id}" method="POST">
    <textarea name="data[text]"></textarea>

    <button type="submit" class="btn btn-small btn-primary">
        {text text='groupthread_submit_comment'}
    </button>
</form>

<h3>{text text='groupthread_fieldset_messages'}</h3>

<ul class="comments">
    {foreach $posts as $item}
        {include "comment.tpl"}
        {foreachelse}
        {text text=$viewName|cat:'_no_comments_found'}
    {/foreach}
</ul>

{strip}
    <div class="pagination">
        {$pagination->getPagesLinks()}
    </div>
{/strip}
