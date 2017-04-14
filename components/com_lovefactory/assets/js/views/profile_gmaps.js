var map_loaded = false;

jQuery(document).ready(function ($) {
    var show_maps = $.cookie("lovefactory_show_maps");
    show_maps = null == show_maps ? 1 : show_maps;

    if (1 == show_maps) {
        LovefactoryInitMap();
    }

    // Google maps toggle
    $("#lovefactory-toggle-maps").click(function (event) {
        event.preventDefault();

        var show_maps = $.cookie("lovefactory_show_maps");
        show_maps = null == show_maps ? 1 : show_maps;
        show_maps = 0 == show_maps ? 1 : 0;

        $.cookie("lovefactory_show_maps", show_maps);

        if (show_maps) {
            $("#google-map").slideToggle(function () {
                if (!map_loaded) {
                    LovefactoryInitMap();
                }
            });

            $(this)
                .addClass("lovefactory-toggle-minus")
                .removeClass("lovefactory-toggle-plus")
                .find("span").html(txt_hide);
        }
        else {
            $("#google-map").slideToggle();
            $(this)
                .removeClass("lovefactory-toggle-minus")
                .addClass("lovefactory-toggle-plus")
                .find("span").html(txt_show);
        }
    });

    function LovefactoryInitMap(zoom) {
        var map = new GMap2(document.getElementById("google-map"));
        var start = new GLatLng(lf_gmap_location.lat, lf_gmap_location.lng);
        var marker;

        var zoom = $.cookie("lovefactory_map_zoom");
        zoom = null == zoom ? 10 : parseInt(zoom);

        map.setCenter(start, zoom);
        map.setUIToDefault();

        var newIcon = new GIcon(G_DEFAULT_ICON);

        newIcon.image = lf_pin_src;
        newIcon.iconSize = new GSize(32, 32);
        newIcon.shadow = lf_pin_shadow_src;
        newIcon.shadowSize = new GSize(40, 32);
        newIcon.iconAnchor = new GPoint(5, 30);

        marker = new GMarker(start, {icon: newIcon});
        map.addOverlay(marker);

        GEvent.addListener(map, "zoomend", function () {
            $.cookie("lovefactory_map_zoom", map.getZoom());
        });

        map_loaded = true;
    }
})
