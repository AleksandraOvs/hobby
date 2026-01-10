(function ($) {

    function updateProducts(wrapper) {
        var filters = { action: 'cwc_filter_products' };

        // Атрибуты внутри конкретного шорткода
        $(wrapper).find('.sidebar-list').each(function () {
            var taxonomy = $(this).data('taxonomy');
            var active = $(this).find('a.active').data('slug');
            if (active) filters['filter_' + taxonomy] = active;
        });

        // Цена
        filters.min_price = $(wrapper).find('#min_price').val();
        filters.max_price = $(wrapper).find('#max_price').val();

        // Текущая категория
        filters.current_cat_id = $(wrapper).data('current-cat');

        console.log('AJAX filters to send:', filters); // <-- лог для проверки

        $.ajax({
            url: cwc_ajax_object.ajax_url,
            type: 'POST',
            data: filters,
            beforeSend: function () {
                $('.products-wrapper').fadeTo(200, 0.5);
            },
            success: function (response) {
                console.log('AJAX response:', response); // <-- лог ответа
                if (response.success) {
                    $('.products-wrapper').html(response.data.html).fadeTo(200, 1);
                }
            },
            error: function (xhr, status, error) {
                console.log('AJAX Error:', status, error);
            }
        });
    }

    // Клик по атрибуту
    $(document).on('click', '.filter-item', function (e) {
        e.preventDefault();
        var wrapper = $(this).closest('.sidebar-area-wrapper');
        $(this).toggleClass('active').siblings().removeClass('active');
        updateProducts(wrapper);
    });

    // jQuery UI Slider
    $(".sidebar-area-wrapper").each(function () {
        var wrapper = $(this);
        var slider = wrapper.find("#price-slider");
        slider.slider({
            range: true,
            min: parseInt(slider.data('min')),
            max: parseInt(slider.data('max')),
            values: [
                parseInt(wrapper.find('#min_price').val()),
                parseInt(wrapper.find('#max_price').val())
            ],
            slide: function (event, ui) {
                wrapper.find("#amount").val(ui.values[0] + " - " + ui.values[1]);
                wrapper.find('#min_price').val(ui.values[0]);
                wrapper.find('#max_price').val(ui.values[1]);
            },
            change: function (event, ui) {
                updateProducts(wrapper);
            }
        });

        wrapper.find("#amount").val(slider.slider("values", 0) + " - " + slider.slider("values", 1));
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

})(jQuery);