(function ($) {
    var defaults = {
        map: {
            center: new google.maps.LatLng(0, 0),
            zoom: 0,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            onZoomChanged: function (zoom) {
            },
            onPinDragEnd: function (position) {
            },
            onMapClicked: function (event) {
            }
        },

        radius: {
            center: new google.maps.LatLng(0, 0),
            radius: 100000,
            strokeColor: '#999999',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#cccccc',
            fillOpacity: 0.35
        },

        pin: {
            position: new google.maps.LatLng(0, 0),
            draggable: false,
            icon: new google.maps.MarkerImage(
                (window.location.protocol == 'https:' ? 'https' : 'http') + '://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                new google.maps.Size(32.0, 32.0),
                new google.maps.Point(0, 0),
                new google.maps.Point(15.0, 32.0)),
            shadow: new google.maps.MarkerImage(
                (window.location.protocol == 'https:' ? 'https' : 'http') + '://maps.gstatic.com/mapfiles/shadow50.png',
                new google.maps.Size(37.0, 34.0),
                new google.maps.Point(0, 0),
                new google.maps.Point(10.0, 34.0)),
            onPinDragEnd: function (callback, position) {
                callback(position);
            }
        }
    }

    var methods = {
        // Initialise Google Map.
        init: function (options) {
            return this.each(function () {
                // Initialise variables.
                var $this = $(this);
                var data = $this.data('LoveFactoryGoogleMap');

                // Check if map is initialised.
                if (!data) {
                    // Extend default values with options.
                    options = $.extend(defaults.map, options);

                    var mapOptions = {
                        center: options.center,
                        zoom: options.zoom,
                        mapTypeId: options.mapTypeId
                    };

                    // Create map.
                    var map = new google.maps.Map(this, mapOptions);

                    // Store data.
                    $(this).data('LoveFactoryGoogleMap', {
                        target: $this,
                        map: map,
                        options: options
                    });
                    data = $this.data('LoveFactoryGoogleMap');
                }

                // Register zoom changed event.
                google.maps.event.addListener(map, 'zoom_changed', function (event) {
                    data.options.onZoomChanged(this.getZoom());
                });

                // Register map clicked event.
                google.maps.event.addListener(map, 'click', function (event) {
                    data.options.onMapClicked(event);
                })

                // Trigger onMapReady event.
                $this.trigger({type: 'onMapReady', map: this});
            });
        },

        // Overwrite map options.
        options: function (options) {
            // Initialise variables.
            var $this = $(this);
            var data = $this.data('LoveFactoryGoogleMap');

            // Extend options.
            data.options = $.extend(data.options, options);
        },

        // Create and show the radius.
        radius: function (options) {
            // Initialise variables.
            var $this = $(this);
            var data = $this.data('LoveFactoryGoogleMap');
            options = $.extend(defaults.radius, options);

            // Create radius.
            if (!data.radius) {
                data.radius = new google.maps.Circle();
            }

            data.radius.setMap(data.map);
            data.radius.setOptions(options);
        },

        // Hide radius.
        removeRadius: function (options) {
            // Initialise variables.
            var $this = $(this);
            var data = $this.data('LoveFactoryGoogleMap');

            if (data.radius) {
                data.radius.setMap(null);
            }
        },

        // Create and show the pin.
        pin: function (options) {
            // Initialise variables.
            var $this = $(this);
            var data = $this.data('LoveFactoryGoogleMap');

            if (typeof options == 'undefined') {
                return data.pin;
            }

            // Create pin.
            if (!data.pin) {
                options = $.extend(defaults.pin, options);

                data.pin = new google.maps.Marker(options);

                // Add event listeners
                google.maps.event.addListener(data.pin, 'dragend', function () {
                    options.onPinDragEnd(data.options.onPinDragEnd, this.getPosition());
                });
            }

            data.pin.setMap(data.map);
            data.pin.setOptions(options);

            defaults.pin.onPinDragEnd(data.options.onPinDragEnd, data.pin.getPosition());

            return data.pin;
        },

        // Hide the pin.
        removePin: function () {
            // Initialise variables.
            var $this = $(this);
            var data = $this.data('LoveFactoryGoogleMap');

            if (data.pin) {
                data.pin.setMap(null);
            }
        },

        // Add markers to map.
        markers: function (options) {
            // Initialise variables.
            var $this = $(this),
                data = $this.data('LoveFactoryGoogleMap'),
                markers = [],
                marker;

            // Set default values.
            var defaults = {
                markerClusterer: false,
                markerClustererZoom: 8
            }

            // Extend default values.
            options = $.extend(defaults, options);

            // Parse markers.
            if (options.markers) {
                for (var i = 0, count = options.markers.length; i < count; i++) {
                    var markerData = options.markers[i];

                    switch (markerData.type) {
                        case 'single.link':
                            marker = new LFSingleLinkMarker(markerData, data.map);
                            break;

                        case 'multiple.link':
                        case 'multiple.group':
                            if ('multiple.link' == markerData.type) {
                                marker = new LFMultipleLinkMarker(markerData, data.map);
                            }
                            else {
                                marker = new LFMultipleGroupMarker(markerData, data.map);
                            }

                            if (options.markerClusterer) {
                                for (var j = 1; j < markerData.count; j++) {
                                    markers.push(new LFDefaultMarker(markerData, data.map));
                                }
                            }
                            break;

                        default:
                            marker = null;
                            break;
                    }

                    if (null === marker) {
                        continue;
                    }

                    markers.push(marker);

                    if (!options.markerClusterer) {
                        marker.setMap(data.map);
                    }
                }
            }

            data.markers = {markers: markers, options: options};

            if (options.markerClusterer) {
                data.markers.markerClusterer = new MarkerClusterer(data.map, markers, {
                    maxZoom: options.markerClustererZoom
                });
            }
        },

        // Clear markers
        removeMarkers: function () {
            var $this = $(this);
            var data = $this.data('LoveFactoryGoogleMap');

            if (data.markers) {
                if (!data.markers.options.markerClusterer) {
                    for (var i = 0, count = data.markers.markers.length; i < count; i++) {
                        data.markers.markers[i].setMap(null);
                    }
                } else {
                    data.markers.markerClusterer.clearMarkers();
                }
            }
        },

        // Returns the map object.
        map: function () {
            var $this = $(this);
            var data = $this.data('LoveFactoryGoogleMap');

            if ('undefined' == typeof data || null === data) {
                return false;
            }

            return data.map;
        }
    };

    $.fn.LoveFactoryGoogleMap = function (method) {
        // Method calling logic
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method "' + method + '" does not exist on jQuery.LoveFactoryGoogleMap');
        }
    };
})(jQuery);
