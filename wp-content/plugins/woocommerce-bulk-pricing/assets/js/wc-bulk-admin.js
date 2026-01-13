jQuery(function ($) {

    // Добавить диапазон
    $(document).on('click', '.add-bulk-row', function (e) {
        e.preventDefault();

        const variation = $(this).closest('.woocommerce_variation');
        if (!variation.length) return;

        $(document).on('click', '.add-bulk-row', function () {
            console.log('Clicked!');
            const container = $(this).closest('.form-row').find('.bulk_discount_container');
            console.log(container.length);
        });

        if (!container.length) return;

        const nameInput = container.find('input[name*="bulk_discounts"]').first();
        if (!nameInput.length) return;

        const match = nameInput.attr('name').match(/\[(\d+)\]/);
        if (!match) return;

        const variationId = match[1];

        container.append(
            '<div class="bulk-discount-row">' +
            '<input type="number" min="1" ' +
            'name="bulk_discounts[' + variationId + '][min_qty][]" ' +
            'placeholder="Мин. кол-во">' +
            '<input type="number" step="0.01" min="0" ' +
            'name="bulk_discounts[' + variationId + '][discount][]" ' +
            'placeholder="% Скидки">' +
            '<button type="button" class="button remove-row">✕</button>' +
            '</div>'
        );
    });

    // Удалить диапазон
    $(document).on('click', '.remove-row', function () {
        $(this).closest('.bulk-discount-row').remove();
    });

});