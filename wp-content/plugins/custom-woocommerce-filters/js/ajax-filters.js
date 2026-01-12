(function ($) {
    let cwcIsInit = true;
    let cwcIsUpdating = false;

    function updateProducts(wrapper) {


        if (cwcIsInit || cwcIsUpdating) return;

        cwcIsUpdating = true;

        var filters = { action: 'cwc_filter_products' };

        // Атрибуты
        $(wrapper).find('.sidebar-list').each(function () {
            var taxonomy = $(this).data('taxonomy');
            var active = $(this).find('a.active').data('slug');

            if (active) {
                if (taxonomy === 'instock_filter' && active === 'instock') {
                    // специальное поле для наличия
                    filters['instock'] = true;
                } else {
                    filters['filter_' + taxonomy] = active;
                }
            }
        });

        // Цена
        filters.min_price = $(wrapper).find('#min_price').val();
        filters.max_price = $(wrapper).find('#max_price').val();

        // Сортировка
        var orderby = $('select.orderby').val();
        if (orderby) filters.orderby = orderby;

        // Категория
        filters.current_cat_id = $(wrapper).data('current-cat');

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

    // Клик по атрибуту
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

    // jQuery UI Slider
    // jQuery UI Slider
    $(".sidebar-area-wrapper").each(function () {
        var wrapper = $(this);
        var slider = wrapper.find("#price-slider");

        var minInput = wrapper.find('#min_price');
        var maxInput = wrapper.find('#max_price');

        var min = parseInt(slider.data('min'));
        var max = parseInt(slider.data('max'));

        slider.slider({
            range: true,
            min: min,
            max: max,
            values: [
                parseInt(minInput.val()),
                parseInt(maxInput.val())
            ],
            slide: function (event, ui) {
                minInput.val(ui.values[0]);
                maxInput.val(ui.values[1]);
            },
            change: function () {
                // только синхронизация, без автозапроса
            }
        });

        // Ввод руками → двигаем слайдер
        minInput.on('change', function () {
            var minVal = parseInt($(this).val()) || min;
            var maxVal = parseInt(maxInput.val()) || max;

            if (minVal < min) minVal = min;
            if (minVal > maxVal) minVal = maxVal;

            slider.slider('values', 0, minVal);
            $(this).val(minVal);
        });

        maxInput.on('change', function () {
            var minVal = parseInt(minInput.val()) || min;
            var maxVal = parseInt($(this).val()) || max;

            if (maxVal > max) maxVal = max;
            if (maxVal < minVal) maxVal = minVal;

            slider.slider('values', 1, maxVal);
            $(this).val(maxVal);
        });
    });

    // Очистка фильтров
    $(document).on('click', '#cwc-reset-filters', function (e) {
        e.preventDefault();
        var wrapper = $(this).closest('.sidebar-area-wrapper');

        wrapper.find('.filter-item').removeClass('active');

        var slider = wrapper.find("#price-slider");
        slider.slider("values", [slider.data('min'), slider.data('max')]);
        wrapper.find('#min_price').val(slider.data('min'));
        wrapper.find('#max_price').val(slider.data('max'));
        wrapper.find("#amount").val(slider.data('min') + " - " + slider.data('max'));

        updateProducts(wrapper);
    });

    // Применить 
    $(document).on('click', '#cwc-apply-filters', function (e) {
        e.preventDefault();

        var wrapper = $(this).closest('.sidebar-area-wrapper');
        updateProducts(wrapper);
    });

    // Перехват стандартной сортировки WooCommerce
    $(document).on('change', 'select.orderby', function (e) {
        if (cwcIsInit) return;
        e.preventDefault();

        var wrapper = $('.sidebar-area-wrapper').first();
        updateProducts(wrapper);
    });

    $(window).on('load', function () {
        cwcIsInit = false;
    });

})(jQuery);