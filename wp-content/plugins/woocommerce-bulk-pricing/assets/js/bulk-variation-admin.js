jQuery(function ($) {

    /* ==================================================
     * ПРОСТОЙ ТОВАР
     * ================================================== */
    $(document).on('click', '#add_bulk_discount', function (e) {
        e.preventDefault();

        const $container = $('#bulk_discount_container');
        if (!$container.length) return;

        $container.append(`
            <div class="bulk-discount-row">
                <input type="number" min="1"
                       name="bulk_discounts[min_qty][]"
                       placeholder="Мин. кол-во">

                <input type="number" step="0.01" min="0"
                       name="bulk_discounts[discount][]"
                       placeholder="% Скидки">

                <button type="button" class="button remove-row">Удалить</button>
            </div>
        `);
    });

    /* ==================================================
     * ВАРИАЦИИ — ИСПРАВЛЕНО
     * ================================================== */
    $(document).on('click', '.add-bulk-row', function (e) {
        e.preventDefault();

        const $variation = $(this).closest('.woocommerce_variation');
        if (!$variation.length) return;

        const $container = $variation.find('.bulk_discount_container');
        if (!$container.length) return;

        // 🔑 ЕДИНСТВЕННО ПРАВИЛЬНЫЙ СПОСОБ
        const variationId = $variation.find('.variable_post_id').val();
        if (!variationId) return;

        $container.append(`
            <div class="bulk-discount-row">
                <input type="number" min="1"
                       name="bulk_discounts[${variationId}][min_qty][]"
                       placeholder="Мин. кол-во">

                <input type="number" step="0.01" min="0"
                       name="bulk_discounts[${variationId}][discount][]"
                       placeholder="% Скидки">

                <button type="button" class="button remove-row">✕</button>
            </div>
        `);
    });

    /* ==================================================
     * УДАЛЕНИЕ
     * ================================================== */
    $(document).on('click', '.remove-row', function (e) {
        e.preventDefault();
        $(this).closest('.bulk-discount-row').remove();
    });

});
