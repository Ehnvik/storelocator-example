define([
  "uiComponent",
  "jquery",
  "ko",
  "Gustav_Thesis/js/view/shared-data",
], function (uiComponent, $, ko, sharedData) {
  return uiComponent.extend({
    defaults: {
      template: "Gustav_Thesis/store-map.html",
      mapCenterLatitude: 59.3293,
      mapCenterLongitude: 18.0686,
    },

    initialize() {
      this._super();
      this.initMap();

      this.stores = sharedData.stores;

      this.stores.subscribe(
        function (newStores) {
          this.showMarkers(newStores);
        }.bind(this)
      );
    },

    initMap: function () {
      const self = this;

      this.map = new google.maps.Map($("#map")[0], {
        center: { lat: self.mapCenterLatitude, lng: self.mapCenterLongitude },
        zoom: 9,
      });
    },
    showMarkers(stores) {
      const self = this;

      if (this.markers) {
        this.markers.forEach((marker) => {
          marker.setMap(null);
        });
      }
      this.markers = [];

      stores.forEach((store) => {
        const marker = new google.maps.Marker({
          position: {
            lat: parseFloat(store.latitude),
            lng: parseFloat(store.longitude),
          },
          map: self.map,
          title: store.name,
        });

        this.markers.push(marker);
      });
    },
  });
});
