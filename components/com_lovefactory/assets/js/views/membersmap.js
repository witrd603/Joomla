jQuery(document).ready(function ($) {
    $('#lovefactory-membersmap').bind('onMapReady', function (event) {
        var map = $(event.map);

        // Add members.
        map.LoveFactoryGoogleMap('markers', {
            markers: LoveFactory.get('members', {}),
            markerClusterer: LoveFactory.get('markerClusterer', false),
            markerClustererZoom: LoveFactory.get('markerClustererZoom', 8)
        });
    });
});
