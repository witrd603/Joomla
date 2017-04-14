<div class="map">
    {$map->renderMap('lovefactory-radiussearch', $location)}
</div>

{jhtml _='LoveFactory.BeginForm' url={jroute task='radiussearch.search'} method="GET" name="lovefactory-radius-form" class=""}
{$renderer->render($page)}

<div style="margin-top: 20px;">
    <button type="submit" class="btn btn-small btn-primary"><span
                class="fa fa-fw fa-search"></span>{jtext _='search_submit_button'}</button>
    <span class="loader" style="display: none;"><i class="factory-icon icon-loader"></i></span>
</div>
</form>
