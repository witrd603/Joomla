jQuery(document).ready(function ($) {
    var map = new GMap2(document.getElementById("google-map"));
    var start, marker, zoom;

    start = new GLatLng(lf_gmap_location.lat, lf_gmap_location.lng);
    zoom = lf_gmap_zoom;

    map.setCenter(start, zoom);
    map.setUIToDefault();

    var newIcon = new GIcon(G_DEFAULT_ICON);

    newIcon.image = lf_pin_src;
    newIcon.iconSize = new GSize(32, 32);
    newIcon.shadow = lf_pin_shadow_src;
    newIcon.shadowSize = new GSize(40, 32);
    newIcon.iconAnchor = new GPoint(2, 30);

    if (lf_has_location) {
        marker = new GMarker(start, {icon: newIcon});
        map.addOverlay(marker);
    }

    GEvent.addListener(map, "click", function (overlay, point) {
        map.clearOverlays();

        marker = new GMarker(point, {icon: newIcon});
        map.addOverlay(marker);
        $("#lovefactory-remove-map-location").show();

        $("#gmap_lat").val(point.y);
        $("#gmap_lng").val(point.x);
    });

    // Remove my location
    $("#lovefactory-remove-map-location").click(function (event) {
        event.preventDefault();

        map.clearOverlays();
        $("#gmap_lat").val(0);
        $("#gmap_lng").val(0);

        $(this).hide();
    });
});
