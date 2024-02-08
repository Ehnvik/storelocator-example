define(['jquery', 'jquery-ui-modules/widget'], function ($) {
    'use strict';

    $.widget('gustav.googleMapConfig', {
        options: {
            map: null,
            mapCenterLatitude: 59.3293,
            mapCenterLongitude: 18.0686,
            markers: {},
            currentInfowindow: null,
        },

        // Initializes the map and binds event handlers
        _create: function () {
            this.initMap();
            this._bindEvents();
        },

        // Creates the Google Map instance
        initMap: function () {
            const self = this;
            const mapOptions = {
                center: new google.maps.LatLng(
                    self.options.mapCenterLatitude,
                    self.options.mapCenterLongitude
                ),
                zoom: 8,
            };

            self.map = new google.maps.Map(
                document.getElementById('map'),
                mapOptions
            );
        },

        // Binds custom event handlers to update markers and handle store selection
        _bindEvents: function () {
            const self = this;

            $(document).on('storeListUpdated', function (event, stores) {
                self._updateMarkers(stores);
            });

            $(document).on('storeSelected', function (event, storeId) {
                if (self.options.markers[storeId]) {
                    const marker = self.options.markers[storeId];

                    if (self.options.currentInfowindow) {
                        self.options.currentInfowindow.close();
                    }
                    marker.infowindow.open(self.map, marker);
                    self.options.currentInfowindow = marker.infowindow;
                }
            });
        },

        // Updates the map with new markers for each store and binds infowindow to each marker
        _updateMarkers: function (stores) {
            const self = this;

            // Clear existing markers from the map
            Object.values(self.options.markers).forEach(function (marker) {
                marker.setMap(null);
            });
            self.options.markers = {};

            // Create new markers for each store
            stores.forEach(function (store) {
                const marker = new google.maps.Marker({
                    position: new google.maps.LatLng(
                        store.latitude,
                        store.longitude
                    ),
                    map: self.map,
                    title: store.name,
                });

                // Format the phone number and create infowindow content
                const formattedPhone = store.phone.replace(/^(08)/, '$1-');

                const infowindowContent = `
                    <div class="info-window-container">
                        <h3 class="info-window-name">${store.name}</h3>
                        <p class="info-window-address">${store.address}</p>
                        <div class="info-window-location">
                            <span class="location-city">${store.city},</span>
                            <span class="location-postcode">${store.postcode},</span>
                            <span class="location-country">${store.country}</span>
                        </div>
                        <div class="info-window-contact">
                            <p class="contact-hours">${store.hours}</p>
                            <p class="contact-phone">${formattedPhone}</p>
                        </div>
                    </div>`;
                const infowindow = new google.maps.InfoWindow({
                    content: infowindowContent,
                });

                // Bind click event to open the infowindow
                marker.addListener('click', function () {
                    if (self.options.currentInfowindow) {
                        self.options.currentInfowindow.close();
                    }
                    infowindow.open(self.map, marker);
                    self.options.currentInfowindow = infowindow;
                });

                // Store marker in the widget's options for future reference
                self.options.markers[store.id] = marker;
                marker.infowindow = infowindow;
            });
        },
    });

    return $.gustav.googleMapConfig;
});
