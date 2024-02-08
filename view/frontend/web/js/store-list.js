define(['jquery', 'mage/url'], function ($, urlBuilder) {
    'use strict';

    // Initializes the store list widget with default options and binds events
    $.widget('gustav.storeList', {
        options: {
            actionUrl: '/storelocator/frontend/storelist',
            currentPage: 1,
            pageSize: 5,
            currentCategory: '',
        },

        // Called when the widget is created; initializes the widget
        _create: function () {
            this._bindEvents();
            this._loadStores(this.options.currentPage);
        },

        // Binds event handlers to UI elements
        _bindEvents: function () {
            const self = this;

            $('.reset-button').on('click', function () {
                $('.category-filter').val('');
                $('.store-search-input').val('');
                self.options.currentPage = 1;
                self.options.currentCategory = '';
                self._loadStores(self.options.currentPage);
            });

            this.element.on('click', '.pagination a', function (event) {
                event.preventDefault();
                const page = $(this).data('page');

                console.log(page);
                self._loadStores(page);
            });

            $(document).on('change', '.category-filter', function () {
                self.options.currentPage = 1;
                self.options.currentCategory = $(this).val();
                self._loadStores(
                    self.options.currentPage,
                    self.options.currentCategory
                );
            });

            $(document).on('input', '.store-search-input', function () {
                let searchTerm = $(this).val();

                self.options.currentPage = 1;

                if (searchTerm.length < 3 && searchTerm.length > 0) {
                    return;
                }

                self._loadStores(
                    self.options.currentPage,
                    self.options.currentCategory,
                    searchTerm
                );
            });
        },

        // Fetches and loads stores based on the current page, selected category, and search query
        _loadStores: function (page, categoryId = '', searchQuery = '') {
            const self = this;

            $.ajax({
                url: urlBuilder.build(this.options.actionUrl),
                type: 'GET',
                data: {
                    page: page,
                    limit: this.options.pageSize,
                    category: categoryId,
                    search: searchQuery,
                },
                dataType: 'json',
                success: function (response) {
                    self.options.currentPage = page;
                    self._renderStores(response.stores);
                    self._renderPagination(response.total_count, page);
                    $(document).trigger('storeListUpdated', [response.stores]);
                },
                error: function (error) {
                    $('.js-store-list').text(
                        'Error loading stores: ' + error.message
                    );
                },
            });
        },

        // Renders the store list HTML based on the fetched stores data
        _renderStores: function (stores) {
            let html = '<div class="stores-list">';

            stores.forEach(store => {
                const formattedPhone = store.phone.replace(/^(08)/, '$1-');

                html += `
            <div class="store-container">
                <div class="store-name-container">
                <h3 class="store-name">${store.name}</h3>
                </div>
                <div class="store-info-contact-wrapper">
                <div class="store-info-container">
                <p class="store-address">${store.address}</p>
                <div class="store-location-container">
                <span class="store-city">${store.city},</span>
                <span class="store-postcode">${store.postcode},</span>
                <span class="store-country">${store.country}</span>
                </div>
                </div>
                <div class="store-contact-container">
                <p class="store-hours">${store.hours}</p>
                <p class="store-phone">${formattedPhone}</p>
                </div>
                </div>
                <div class="show-on-map-container">
                    <a href="#" class="show-on-map" data-store-id="${store.id}">Show on Map</a>
                </div>
            </div>
        `;
            });

            html += '</div>';
            this.element.html(html);
            this._addStoreClickListeners();
        },

        // Adds click event listeners to store listings for map interaction
        _addStoreClickListeners: function () {
            this.element.find('.show-on-map').on('click', function () {
                const storeId = $(this).data('store-id');

                $('html, body').animate(
                    {
                        scrollTop: $('#map').offset().top,
                    },
                    1000
                );

                $(document).trigger('storeSelected', storeId);
            });
        },

        // Renders pagination controls based on total stores and current page
        _renderPagination: function (totalCount, currentPage) {
            let pageCount = Math.ceil(totalCount / this.options.pageSize);
            let paginationHtml = '<div class="pagination">';

            if (currentPage > 1) {
                paginationHtml += `<a href="#" data-page="${
                    currentPage - 1
                }" class="action previous">&laquo; Back</a>`;
            }

            for (let i = 1; i <= pageCount; i++) {
                paginationHtml += `<a href="#" data-page="${i}" ${
                    currentPage === i ? 'class="current-page"' : ''
                }>${i}</a>`;
            }

            if (currentPage < pageCount) {
                paginationHtml += `<a href="#" data-page="${
                    currentPage + 1
                }" class="action next">Next &raquo;</a>`;
            }

            paginationHtml += '</div>';
            this.element.append(paginationHtml);
        },
    });

    return $.gustav.storeList;
});
