// product.js
document.addEventListener('DOMContentLoaded', () => {

    const formatPrice = price =>
        new Intl.NumberFormat('ru-RU', {
            style: 'currency',
            currency: 'RUB'
        }).format(price);

    /* ===============================
       SIMPLE PRODUCT
    =============================== */

    const simpleForm = document.querySelector('.single-product-add-to-cart form.cart');

    if (simpleForm) {
        const qty = simpleForm.querySelector('input.qty');
        const total = simpleForm.querySelector('.price-total');
        const base = simpleForm.querySelector('.price-single');

        if (qty && total && base) {
            const update = () => {
                const price = parseFloat(base.dataset.basePrice || 0);
                const count = parseInt(qty.value, 10) || 1;
                total.textContent = formatPrice(price * count);
            };

            qty.addEventListener('input', update);
            simpleForm.querySelectorAll('.qty-btn')
                .forEach(btn => btn.addEventListener('click', () => setTimeout(update, 0)));

            update();
        }
    }

    /* ===============================
       VARIATION PRODUCT
    =============================== */

    const $variationForm = jQuery('form.variations_form');

    if ($variationForm.length) {

        const container = $variationForm.find('.cart__price-update');
        const qty = container.find('input.qty');
        const total = container.find('.price-total');

        const update = () => {
            const base = parseFloat(container.data('price') || 0);
            const count = parseInt(qty.val(), 10) || 1;
            total.text(formatPrice(base * count));
        };

        qty.on('input', update);

        // ← ВОТ КЛЮЧЕВОЙ МОМЕНТ
        $variationForm.on('found_variation', function (event, variation) {
            container.data('price', variation.display_price || 0);
            update();
        });

        // если вариация сброшена
        $variationForm.on('reset_data', function () {
            container.data('price', 0);
            update();
        });
    }

});