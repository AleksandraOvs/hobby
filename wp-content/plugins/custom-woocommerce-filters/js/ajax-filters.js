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
         * Атрибуты (checkbox / radio)
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
         * Цена (ТОЛЬКО если сужена)
         * ------------------- */
        var minPriceInput = $(wrapper).find('#min_price');
        var maxPriceInput = $(wrapper).find('#max_price');
        var priceSlider = $(wrapper).find('#price-slider');

        if (priceSlider.length) {
            var priceMinDef = parseInt(priceSlider.data('min'), 10);
            var priceMaxDef = parseInt(priceSlider.data('max'), 10);

            var priceMin = parseInt(minPriceInput.val(), 10);
            var priceMax = parseInt(maxPriceInput.val(), 10);

            if (priceMin > priceMinDef || priceMax < priceMaxDef) {
                filters.min_price = priceMin;
                filters.max_price = priceMax;
            }
        }

        /* -------------------
         * Числовые атрибуты (✅ FIX)
         * Отправляем ТОЛЬКО если диапазон изменён
         * ------------------- */
        $(wrapper).find('.range-inputs').each(function () {

            var $minInput = $(this).find('input[name$="_min"]');
            var $maxInput = $(this).find('input[name$="_max"]');

            if (!$minInput.length || !$maxInput.length) return;

            var minVal = parseFloat($minInput.val());
            var maxVal = parseFloat($maxInput.val());

            var minDef = parseFloat($minInput.attr('min'));
            var maxDef = parseFloat($maxInput.attr('max'));

            // пользователь НЕ трогал диапазон → не участвует в запросе
            if (minVal === minDef && maxVal === maxDef) return;

            // пользователь сузил → отправляем
            filters[$minInput.attr('name')] = minVal;
            filters[$maxInput.attr('name')] = maxVal;
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

                    initShowMoreFilters(document);
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
    });

    /* -------------------
     * Сброс фильтров
     * ------------------- */
    $(document).on('click', '#cwc-reset-filters', function (e) {
        e.preventDefault();

        var wrapper = $(this).closest('.sidebar-area-wrapper');

        wrapper.find('.filter-item').removeClass('active');

        // цена
        var slider = wrapper.find('#price-slider');
        if (slider.length) {
            slider.slider('values', [
                slider.data('min'),
                slider.data('max')
            ]);

            wrapper.find('#min_price').val(slider.data('min'));
            wrapper.find('#max_price').val(slider.data('max'));
        }

        // числовые атрибуты
        wrapper.find('.range-inputs').each(function () {
            var $min = $(this).find('input[name$="_min"]');
            var $max = $(this).find('input[name$="_max"]');

            $min.val($min.attr('min'));
            $max.val($max.attr('max'));
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

    /* -------------------
 * Показать ещё (атрибуты)
 * ------------------- */
    function initShowMoreFilters(context) {

        $(context).find('ul.sidebar-list').each(function () {

            var $list = $(this);
            var $items = $list.find('li');

            if ($items.length <= 5) return;

            // прячем всё после 5
            $items.slice(5).hide();

            // защита от повторного добавления кнопки
            if ($list.next('.cwc-show-more').length) return;

            var $btn = $('<button type="button" class="cwc-show-more">Показать ещё</button>');

            $btn.on('click', function () {

                var expanded = $btn.hasClass('opened');

                if (!expanded) {
                    $items.show();
                    $btn
                        .addClass('opened')
                        .text('Скрыть');
                } else {
                    $items.slice(5).hide();
                    $btn
                        .removeClass('opened')
                        .text('Показать ещё');
                }
            });

            $list.after($btn);
        });
    }

    $(window).on('load', function () {
        cwcIsInit = false;
        initShowMoreFilters(document);
    });

})(jQuery);
