define([
  "uiComponent",
  "jquery",
  "ko",
  "uiLayout",
  "mage/url",
  "Gustav_Thesis/js/view/shared-data",
], function (uiComponent, $, ko, uiLayout, urlBuilder, sharedData) {
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
      searchQuery: ko.observable("").extend({
        rateLimit: { timeout: 500, method: "notifyWhenChangesStop" },
      }),
      listens: {
        categoryId: "loadStores",
        searchQuery: "checkSearchInputLength",
      },
    },

    initialize() {
      this._super();

      this.stores = sharedData.stores;

      this.pageCount = ko.computed(() => {
        return Math.ceil(this.totalCount() / this.pageSize);
      }, this);

      const categoriesObj = JSON.parse(this.categoriesData);
      const categoriesArray = Object.keys(categoriesObj).map((key) => {
        return categoriesObj[key];
      });

      this.categories(categoriesArray);

      this.loadStores();
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
          search: self.searchQuery(),
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

    resetStorePage() {
      this.categoryId("");
      this.searchQuery("");
      this.currentPage(1);
      this.loadStores();
    },

    checkSearchInputLength() {
      if (this.searchQuery().length >= 3 || this.searchQuery() === "") {
        this.loadStores();
      }
    },
  });
});
