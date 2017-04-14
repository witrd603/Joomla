<div id="lovefactory-dialog" title="{jtext _='dialog_relationship_dialog_title'}">
    <div class="lovefactory-dialog-relationship lovefactory-view">
        <div class="lovefactory-dialog-content">

            <form action="{jroute task='relationship.request'}" method="post">
                {if $settings->friendship_request_message}
                    <table>
                        <tr>
                            <th><label>{jtext _='dialog_quickmessage_label_to'}</label></th>
                            <td>{$username}</td>
                        </tr>

                        <tr>
                            <th><label for="message">{jtext _='dialog_relationship_label_message'}</label></th>
                            <td><textarea rows="5" cols="10" id="message" name="message"></textarea></td>
                        </tr>
                    </table>
                {else}
                    {jtext sprintf='dialog_relationship_request_generic_message' username=$username}
                {/if}

                <input type="hidden" name="user_id" value="{$userId}">
            </form>

        </div>

        <div class="lovefactory-dialog-buttons">
            <a href="#" class="dialog-button dialog-button-submit ui-state-hover"><i
                        class="factory-icon icon-user-plus"></i>{jtext _='dialog_relationship_button_submit'}</a>
            <a href="#" class="dialog-button dialog-button-close"><i
                        class="factory-icon icon-cross-button"></i>{jtext _='dialog_button_cancel'}</a>
        </div>
    </div>
</div>
