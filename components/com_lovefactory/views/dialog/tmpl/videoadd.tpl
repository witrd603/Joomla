<div id="lovefactory-dialog" title="{jtext _='dialog_videoadd_dialog_title'}">
    <div class="lovefactory-dialog-friendship lovefactory-view">
        <div class="lovefactory-dialog-content">

            {if $settings->enable_youtube_integration}
                <div class="dialog-tip">
                    <i class="factory-icon icon-light-bulb"></i>
                    <div>{jtext _='dialog_videoadd_youtube_tip'}</div>
                </div>
            {/if}

            <form action="{jroute task='video.add'}" method="post" enctype="multipart/form-data">
                <table>
                    <tr>
                        <th><label for="video_title">{jtext _='dialog_videoadd_title_label'}</label></th>
                        <td><input type="text" name="video[title]" id="video_title"/></td>
                    </tr>

                    <tr>
                        <th><label for="video_description">{jtext _='dialog_video_description_label'}</label></th>
                        <td><textarea rows="5" cols="10" id="video_description" name="video[description]"></textarea>
                        </td>
                    </tr>

                    <tr>
                        <th><label for="video_code">{jtext _='dialog_vide_embed_code_label'}</label></th>
                        <td>
                            <textarea rows="5" cols="10" id="video_code" name="video[code]"></textarea>

                            {if $settings->enable_youtube_integration}
                                <div style="margin-top: 5px;">
                                    <i class="factory-icon icon-loader" style="display: none;"></i><a href="#"
                                                                                                      class="retrieve-youtube-data">{jtext _='dialog_videoadd_youtube_get_data'}</a>
                                </div>
                            {/if}
                        </td>
                    </tr>

                    <tr>
                        <th><label for="video_thumbnail">{jtext _='dialog_video_thumbnail_label'}</label></th>
                        <td>
                            <div class="video-youtube-thumbnail" style="display: none;">
                                <img src=""/>
                                <input type="hidden" id="video_thumbnail_external" name="video[thumbnail_external]"
                                       disabled="disabled"/>
                            </div>
                            <input type="file" id="video_thumbnail" name="video[thumbnail]"/>
                        </td>
                    </tr>
                </table>
            </form>

        </div>

        <div class="lovefactory-dialog-buttons">
            <a href="#" class="dialog-button dialog-button-submit ui-state-hover"><i
                        class="factory-icon icon-film-plus"></i>{jtext _='dialog_videoadd_button_submit'}</a>
            <a href="#" class="dialog-button dialog-button-close"><i
                        class="factory-icon icon-cross-button"></i>{jtext _='dialog_button_cancel'}</a>
        </div>
    </div>
</div>
