define(["uiComponent", "jquery", "ko", "uiLayout", "mage/url"], function (
  uiComponent,
  $,
  ko,
  uiLayout,
  urlBuilder
) {
  return uiComponent.extend({
    defaults: {
      template: "Gustav_Thesis/store-list.html",
      actionUrl: "",
      stores: ko.observableArray([]),
    },

    initialize() {
      this._super();
      console.log("Hello Ui Component");
      this.loadStores();
    },

    loadStores() {
      const self = this;

      $.ajax({
        url: urlBuilder.build(this.actionUrl),
        type: "GET",
        dataType: "json",

        success: function (response) {
          console.log("Stores: ", response);
          self.stores(response.stores);
        },
        error: function (error) {
          console.log("Error: ", error);
        },
      });
    },
  });
});
