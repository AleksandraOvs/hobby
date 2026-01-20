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

    const variationForm = document.querySelector('form.variations_form');

    if (variationForm) {
        const container = variationForm.querySelector('.cart__price-update');
        if (!container) return;

        const qty = container.querySelector('input.qty');
        const total = container.querySelector('.price-total');

        const update = () => {
            const base = parseFloat(container.dataset.price || 0);
            const count = parseInt(qty.value, 10) || 1;
            total.textContent = formatPrice(base * count);
        };

        qty.addEventListener('input', update);

        variationForm.addEventListener('found_variation', e => {
            container.dataset.price = e.detail.display_price || 0;
            update();
        });
    }

});
