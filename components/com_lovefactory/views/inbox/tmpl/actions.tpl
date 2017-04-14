<div>
    <span class="batch-label">{text text='messages_label_batch'}</span>

    <button type="button" onclick="this.form.task.value='delete'; this.form.submit();" class="btn btn-small btn-danger">
        <span class="fa fa-fw fa-times"></span>{text text='messages_button_delete'}
    </button>

    {if "inbox" == $viewName}
        <button type="button" onclick="this.form.task.value='mark'; this.form.submit();" class="btn btn-small">
            <span class="fa fa-fw fa-check"></span>{text text='inbox_button_mark_as_read'}
        </button>
    {/if}

    {if "outbox" == $viewName && $approval}
        <div class="pull-right small muted">
            <span class="fa fa-fw fa-warning text-danger"></span>{text text="outbox_pending_items_info"}
        </div>
    {/if}
</div>
