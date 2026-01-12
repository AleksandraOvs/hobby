(function ($) {

    let cwcIsInit = true;
    let cwcIsUpdating = false;

    function updateProducts(wrapper) {

        if (cwcIsInit || cwcIsUpdating) return;
        cwcIsUpdating = true;

        var filters = {
            action: 'cwc_filter_products'
        };

        /* -------------------
         * Атрибуты (checkbox)
         * ------------------- */
        $(wrapper).find('.sidebar-list').each(function () {
            var taxonomy = $(this).data('taxonomy');
            var active = $(this).find('a.active').data('slug');

            if (!active) return;

            if (taxonomy === 'instock_filter' && active === 'instock') {
                filters.instock = true;
            } else {
                filters['filter_' + taxonomy] = active;
            }
        });

        /* -------------------
         * Цена
         * ------------------- */
        var minPrice = $(wrapper).find('#min_price').val();
        var maxPrice = $(wrapper).find('#max_price').val();

        if (minPrice !== '') filters.min_price = minPrice;
        if (maxPrice !== '') filters.max_price = maxPrice;

        /* -------------------
         * Числовые атрибуты
         * ------------------- */
        $(wrapper).find('input[name^="filter_pa_"]').each(function () {
            var name = $(this).attr('name');
            var val = $(this).val();

            if (val !== '' && !isNaN(val)) {
                filters[name] = val;
            }
        });

        /* -------------------
         * Сортировка
         * ------------------- */
        var orderby = $('select.orderby').val();
        if (orderby) {
            filters.orderby = orderby;
        }

        /* -------------------
         * Категория
         * ------------------- */
        var currentCat = $(wrapper).data('current-cat');
        if (currentCat) {
            filters.current_cat_id = currentCat;
        }

        /* -------------------
         * AJAX
         * ------------------- */
        $.ajax({
            url: cwc_ajax_object.ajax_url,
            type: 'POST',
            data: filters,
            beforeSend: function () {
                $('.products-wrapper').fadeTo(200, 0.5);
            },
            success: function (response) {
                if (response.success) {
                    $('.products-wrapper')
                        .html(response.data.html)
                        .fadeTo(200, 1);
                }
            },
            complete: function () {
                cwcIsUpdating = false;
            }
        });
    }

    /* -------------------
     * Клик по атрибуту
     * ------------------- */
    $(document).on('click', '.filter-item, .filter-item *', function (e) {
        e.preventDefault();

        var $item = $(this).closest('.filter-item');

        $item
            .toggleClass('active')
            .closest('li')
            .siblings()
            .find('.filter-item')
            .removeClass('active');
    });

    /* -------------------
     * Слайдер цены
     * ------------------- */
    $('.sidebar-area-wrapper').each(function () {

        var wrapper = $(this);
        var slider = wrapper.find('#price-slider');

        if (!slider.length) return;

        var minInput = wrapper.find('#min_price');
        var maxInput = wrapper.find('#max_price');

        var min = parseInt(slider.data('min'), 10);
        var max = parseInt(slider.data('max'), 10);

        slider.slider({
            range: true,
            min: min,
            max: max,
            values: [
                parseInt(minInput.val(), 10),
                parseInt(maxInput.val(), 10)
            ],
            slide: function (event, ui) {
                minInput.val(ui.values[0]);
                maxInput.val(ui.values[1]);
            }
        });

        minInput.on('change', function () {
            var minVal = parseInt($(this).val(), 10);
            var maxVal = parseInt(maxInput.val(), 10);

            if (isNaN(minVal)) minVal = min;
            if (minVal < min) minVal = min;
            if (minVal > maxVal) minVal = maxVal;

            slider.slider('values', 0, minVal);
            $(this).val(minVal);
        });

        maxInput.on('change', function () {
            var minVal = parseInt(minInput.val(), 10);
            var maxVal = parseInt($(this).val(), 10);

            if (isNaN(maxVal)) maxVal = max;
            if (maxVal > max) maxVal = max;
            if (maxVal < minVal) maxVal = minVal;

            slider.slider('values', 1, maxVal);
            $(this).val(maxVal);
        });
    });

    /* -------------------
     * Сброс фильтров
     * ------------------- */
    $(document).on('click', '#cwc-reset-filters', function (e) {
        e.preventDefault();

        var wrapper = $(this).closest('.sidebar-area-wrapper');

        wrapper.find('.filter-item').removeClass('active');

        // Цена
        var slider = wrapper.find('#price-slider');
        if (slider.length) {
            slider.slider('values', [
                slider.data('min'),
                slider.data('max')
            ]);

            wrapper.find('#min_price').val(slider.data('min'));
            wrapper.find('#max_price').val(slider.data('max'));
        }

        // Числовые атрибуты
        wrapper.find('input[name^="filter_pa_"]').each(function () {
            var min = $(this).attr('min');
            var max = $(this).attr('max');

            if ($(this).attr('name').endsWith('_min')) {
                $(this).val(min);
            }
            if ($(this).attr('name').endsWith('_max')) {
                $(this).val(max);
            }
        });

        updateProducts(wrapper);
    });

    /* -------------------
     * Применить
     * ------------------- */
    $(document).on('click', '#cwc-apply-filters', function (e) {
        e.preventDefault();
        updateProducts($(this).closest('.sidebar-area-wrapper'));
    });

    /* -------------------
     * Сортировка Woo
     * ------------------- */
    $(document).on('change', 'select.orderby', function (e) {
        if (cwcIsInit) return;
        e.preventDefault();
        updateProducts($('.sidebar-area-wrapper').first());
    });

    $(window).on('load', function () {
        cwcIsInit = false;
    });

})(jQuery);
