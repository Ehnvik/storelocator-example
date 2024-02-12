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
      totalCount: ko.observable(0),
      currentPage: ko.observable(1),
      pageSize: 5,
      errorMessage: ko.observable(""),
      categoriesData: "",
      categories: ko.observableArray([]),
      categoryId: ko.observable(""),
    },

    initialize() {
      this._super();
      this.pageCount = ko.computed(() => {
        return Math.ceil(this.totalCount() / this.pageSize);
      }, this);
      this.loadStores();
      this.categories(JSON.parse(this.categoriesData));
    },

    loadStores() {
      const self = this;

      $.ajax({
        url: urlBuilder.build(this.actionUrl),
        type: "GET",
        dataType: "json",
        data: {
          page: this.currentPage(),
          category: self.categoryId(),
        },

        success: function (response) {
          self.stores(response.stores);
          self.totalCount(response.total_count);
        },
        error: function (error) {
          self.errorMessage("Could not load stores: " + error.status);
        },
      });
    },

    changePage(newPage) {
      if (newPage >= 1 && newPage <= this.pageCount()) {
        this.currentPage(newPage);
        this.loadStores();
      }
    },
  });
});
