<div id="lovefactory-dialog" title="{jtext _='dialog_report_dialog_title'}">
    <div class="lovefactory-dialog-report lovefactory-view">
        <div class="lovefactory-dialog-content">

            <form action="{jroute task='report.send'}" method="post">
                <p>{jtext _='dialog_report_extra_info'}</p>
                <table>
                    <tr>
                        <th><label for="message">{jtext _='dialog_friendship_label_message'}</label></th>
                        <td><textarea rows="5" cols="10" id="message" name="data[message]"></textarea></td>
                    </tr>
                </table>

                <input type="hidden" name="data[type]" value="{$params->get('type')}">
                <input type="hidden" name="data[id]" value="{$params->get('id')}">
            </form>

        </div>

        <div class="lovefactory-dialog-buttons">
            <a href="#" class="dialog-button dialog-button-submit ui-state-hover"><i
                        class="factory-icon icon-arrow-000-medium"></i>{jtext _='dialog_report_button_submit'}</a>
            <a href="#" class="dialog-button dialog-button-close"><i
                        class="factory-icon icon-cross-button"></i>{jtext _='dialog_button_cancel'}</a>
        </div>
    </div>
</div>
