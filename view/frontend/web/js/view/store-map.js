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
      currentInfowindow: null,
    },

    initialize() {
      this._super();
      this.initMap();

      this.stores = sharedData.stores;

      this.stores.subscribe((newStores) => {
        this.clearMarkers();
        this.showMarkers(newStores);
      });
    },

    initMap() {
      this.map = new google.maps.Map($("#map")[0], {
        center: { lat: this.mapCenterLatitude, lng: this.mapCenterLongitude },
        zoom: 9,
      });
      this.markers = [];
    },

    clearMarkers() {
      this.markers.forEach((marker) => marker.setMap(null));
      this.markers = [];
    },

    showMarkers(stores) {
      stores.forEach((store) => {
        let marker = new google.maps.Marker({
          position: {
            lat: parseFloat(store.latitude),
            lng: parseFloat(store.longitude),
          },
          map: this.map,
          title: store.name,
        });

        marker.addListener("click", () => this.openInfowindow(marker, store));
        this.markers.push(marker);
      });
    },

    openInfowindow(marker, store) {
      const formattedPhone = this.formatPhone(store.phone);
      const content = this.getInfowindowContent(store, formattedPhone);

      if (this.currentInfowindow) {
        this.currentInfowindow.close();
      }

      this.currentInfowindow = new google.maps.InfoWindow({ content });
      this.currentInfowindow.open(this.map, marker);
    },

    getInfowindowContent(store, formattedPhone) {
      return `
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
    },

    formatPhone(phone) {
      return phone.replace(/^(08|07\d)/, "$1-");
    },
  });
});
