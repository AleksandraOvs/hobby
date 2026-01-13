jQuery(function ($) {
    const container = $('.bulk-discount-flex').parent();

    $('form.variations_form')
        .on('found_variation', function (event, variation) {

            if (!variation.variation_id) return;

            $.post(
                wc_bulk_discount.ajax_url,
                {
                    action: 'get_bulk_discount_table',
                    variation_id: variation.variation_id
                },
                function (response) {
                    if (response.success) {
                        container.html(response.data);
                    } else {
                        container.empty();
                    }
                }
            );
        })
        .on('reset_data', function () {
            container.empty();
        });
});
