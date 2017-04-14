<div id="lovefactory-dialog" title="{jtext _='dialog_quickmessage_dialog_title'}">
    <div class="lovefactory-dialog-quickmessage lovefactory-view">
        <div class="lovefactory-dialog-content">

            <form action="{jroute task='message.send'}" method="post">
                <table>

                    <tr>
                        <th><label>{jtext _='dialog_quickmessage_label_to'}</label></th>
                        <td>{$username}</td>
                    </tr>

                    <tr>
                        <th><label for="text">{jtext _='dialog_quickmessage_label_message'}</label></th>
                        <td><textarea rows="5" cols="10" id="text" name="message[text]"></textarea></td>
                    </tr>
                </table>

                <input type="hidden" name="message[user_id]" value="{$userId}">
            </form>

        </div>

        <div class="lovefactory-dialog-buttons">
            <a href="#" class="dialog-button dialog-button-submit ui-state-hover"><i
                        class="factory-icon icon-mail-arrow"></i>{jtext _='dialog_quickmessage_button_submit'}</a>
            <a href="#" class="dialog-button dialog-button-close"><i
                        class="factory-icon icon-cross-button"></i>{jtext _='dialog_button_cancel'}</a>
        </div>
    </div>
</div>
