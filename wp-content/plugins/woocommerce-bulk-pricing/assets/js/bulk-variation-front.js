jQuery(function ($) {

    const $wrapper = $('.bulk-discount-wrapper'); // обёртка для таблицы
    if (!$wrapper.length) return;

    $('form.variations_form').on('found_variation', function (e, variation) {

        if (!variation || !variation.bulk_discounts || !variation.bulk_discounts.length) {
            $wrapper.empty();
            return;
        }

        const basePrice = parseFloat(variation.display_price || variation.price || 0);
        if (!basePrice) {
            $wrapper.empty();
            return;
        }

        let html = '<div class="bulk-discount-flex"><h4>Скидки</h4>';

        variation.bulk_discounts.forEach(row => {
            const final = basePrice * (1 - row.discount / 100);
            html += `
                <div class="bulk-discount-item">
                    <span class="bulk-min-qty">От ${row.min_qty} шт.</span>
                    <span class="bulk-price">${variation.currency_symbol}${final.toFixed(2)}/шт.</span>
                    <span class="bulk-discount">
                        <span class="_percent">-${row.discount}%</span>
                    </span>
                </div>
            `;
        });

        html += '</div>';

        $wrapper.html(html);
    });

    // если выбор вариации сброшен
    $('form.variations_form').on('reset_data', function () {
        $wrapper.empty();
    });

});