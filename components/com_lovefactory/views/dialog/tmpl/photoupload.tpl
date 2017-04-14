<div id="lovefactory-dialog" title="{jtext _='dialog_photoupload_dialog_title'}">
    <div class="lovefactory-dialog-friendship lovefactory-view">
        <div class="lovefactory-dialog-content" style="max-height: 400px; overflow:auto;">

            <form action="{jroute task='photo.upload'}" method="post" enctype="multipart/form-data"
                  style="position:relative;">
                <div id="progressbar"></div>

                <ul class="files"></ul>

                <input type="file" id="batch" name="batch[]"
                       style="{if !$test}visibility: hidden; position: absolute; top: -200px;{/if}"
                       multiple="multiple"/>
            </form>

        </div>

        <div class="lovefactory-dialog-buttons">
            <select style="margin: 0; display: none;">
                <option value="0">{jtext _='photos_privacy_public'}</option>

                {if $settings->enable_friends}
                    <option value="1">{jtext _='photos_privacy_friends'}</option>
                {/if}

                <option value="2">{jtext _='photos_privacy_private'}</option>
            </select>

            <a href="#" class="dialog-button dialog-button-select ui-state-hover"><i
                        class="factory-icon icon-mouse-select"></i>{jtext _='dialog_photoupload_button_select_files'}
            </a>
            <a href="#" class="dialog-button dialog-button-submit ui-state-hover"><i
                        class="factory-icon icon-drive-upload"></i>{jtext _='dialog_photoupload_button_submit'}</a>
            <a href="#" class="dialog-button dialog-button-close"><i
                        class="factory-icon icon-cross-button"></i>{jtext _='dialog_button_cancel'}</a>
        </div>
    </div>
</div>
