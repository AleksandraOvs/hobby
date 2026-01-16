jQuery(function ($) {

    const $wrapper = $('.bulk-discount-wrapper');
    if (!$wrapper.length) return;

    // Когда выбрана вариация
    $('form.variations_form').on('found_variation', function (e, variation) {

        if (!variation || !variation.variation_id) {
            $wrapper.empty();
            return;
        }

        $.post(
            wc_bulk_discount.ajax_url,
            {
                action: 'get_bulk_discount_table',
                variation_id: variation.variation_id
            },
            function (response) {
                if (response.success) {
                    $wrapper.html(response.data);
                } else {
                    $wrapper.empty();
                }
            }
        );
    });

    // Когда выбор вариации сброшен
    $('form.variations_form').on('reset_data', function () {
        $wrapper.empty();
    });

});
