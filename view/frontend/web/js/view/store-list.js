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
      listens: {
        categoryId: "loadStores",
      },
    },

    initialize() {
      this._super();
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
        },

        success: function (response) {
          self.stores(response.stores);
          self.totalCount(response.total_count);
          console.log(self.categoryId());
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
