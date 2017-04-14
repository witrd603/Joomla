{extends "layout.tpl"}

{block "heading"}
    {text sprintf="message_heading_title" title=$item->title}
{/block}

{block "toolbar"}
    {toolbar toolbar="messages"}
{/block}

{block "content"}
    <div class="actions">
        {if $item->sender_id}
            <a href="{route view="profile" user_id=$item->sender_id}">
                {$item->getSenderUsername()}</a>
        {else}
            <b>{text text='messages_system_message'}</b>
        {/if}

        {text text='message_to'}

        <a href="{route view="profile" user_id=$item->receiver_id}">
            {$item->getReceiverUsername()}</a>

        <span class="small muted">
      <span class="fa fa-fw fa-clock-o"></span>{date date=$item->date}
    </span>

        <div class="pull-right">
            {if $item->sender_id}
                <a href="{route view="compose" reply_id=$item->id}">
                    <span class="fa fa-fw fa-mail-reply"></span>{text text='message_button_reply'}</a>
            {/if}

            <a href="{route controller="message" task="delete" id=$item->id}">
                <span class="fa fa-fw fa-times"></span>{text text='message_button_delete'}</a>

            {if $item->sender_id}
                {report type='message' id=$item->id reported=$item->reported options=['style' => 'new']}
            {/if}
        </div>
    </div>
    {$item->text|nl2p}
{/block}
