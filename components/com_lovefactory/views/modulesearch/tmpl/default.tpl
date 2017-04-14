<div class="lovefactory-view view-search view-module-search">
    {jhtml _='LoveFactory.BeginForm' url={jroute view=$type|cat:$Itemid}|cat:$jumpToResults method='GET' name=''}
    {$renderer->render($page, $request)}

    <div style="margin-top: 10px;">
        <button type="submit" class="btn btn-small btn-primary">
            <span class="fa fa-fw fa-search"></span>{text text="search_submit_button"}
        </button>

        <a href="#" class="lovefactory-form-reset btn btn-small btn-link">
            <span class="fa fa-fw fa-times"></span>{text text="search_reset_form"}
        </a>
    </div>
    </form>
</div>
