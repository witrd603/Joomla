(function ($) {
    var methods = {

        renderInputEdit: function (options) {
            var map = $('#' + options.id);
            var zoomInput = $('#' + options.id + '_zoom');
            var latInput = $('#' + options.id + '_lat');
            var lngInput = $('#' + options.id + '_lng');
            var removeLink = $('#' + options.id + '_remove');

            // Add draggable pin.
            var params = {draggable: true}
            if (options.position) {
                params.position = new google.maps.LatLng(options.position.lat, options.position.lng);
            } else {
                params.map = null;
            }
            map.LoveFactoryGoogleMap('pin', params);

            // Register map event listeners.
            map.LoveFactoryGoogleMap('options', {
                onZoomChanged: function (zoom) {
                    zoomInput.val(zoom);
                },

                onPinDragEnd: function (position) {
                    latInput.val(position.lat());
                    lngInput.val(position.lng());
                },

                onMapClicked: function (event) {
                    map.LoveFactoryGoogleMap('removePin');
                    map.LoveFactoryGoogleMap('pin', {position: event.latLng})

                    zoomInput.attr('disabled', false);
                    latInput.attr('disabled', false);
                    lngInput.attr('disabled', false);
                }
            });

            // Remove location link
            removeLink.click(function (event) {
                event.preventDefault();

                zoomInput.attr('disabled', true);
                latInput.attr('disabled', true);
                lngInput.attr('disabled', true);

                map.LoveFactoryGoogleMap('removePin');
            });
        },

        renderInputView: function (options) {
            var map = $('#' + options.id);

            // Add draggable pin.
            map.LoveFactoryGoogleMap('pin', {
                position: new google.maps.LatLng(options.lat, options.lng),
                draggable: false
            });
        }

    };

    $.LoveFactoryFieldGoogleMapsLocation = function (method) {
        // Method calling logic
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method "' + method + '" does not exist on jQuery.LoveFactoryFieldGoogleMapsLocation');
        }
    };
})(jQuery);
