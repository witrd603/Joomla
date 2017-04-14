jQuery(document).ready(function ($) {
    var map = $('#lovefactory-radiussearch');

    map.bind('onMapReady', function (event) {
        var map = $(event.map);
        var location = LoveFactory.get('location', {});
        var options = {
            position: new google.maps.LatLng(location.lat, location.lng),
            draggable: true,
            onPinDragEnd: function (event, position) {
                $('#form_radius_lat').val(position.lat());
                $('#form_radius_lng').val(position.lng());
            }
        }

        map.LoveFactoryGoogleMap('pin', options);
    });

    $('#lovefactory-radius-form').submit(function (event) {
        event.preventDefault();

        // Initialise variables.
        var elem = $(this);
        var data = elem.serialize();
        var url = elem.attr('action');

        $('.loader').show();

        // Clear map.
        map.LoveFactoryGoogleMap('removeRadius');
        map.LoveFactoryGoogleMap('removeMarkers');

        // Get search results.
        $.get(url, data, function (response) {
            var pin = map.LoveFactoryGoogleMap('pin');
            $('.loader').hide();

            // Update radius.
            map.LoveFactoryGoogleMap('radius', {
                center: pin.getPosition(),
                radius: response.distance * 1000,
                draggable: false,
                editable: false
            });

            // Add markers to the map.
            map.LoveFactoryGoogleMap('markers', {
                markers: response.results,
                markerClusterer: LoveFactory.get('markerClusterer', false),
                markerClustererZoom: LoveFactory.get('markerClustererZoom', 8)
            });
        }, 'json');
    });
});
