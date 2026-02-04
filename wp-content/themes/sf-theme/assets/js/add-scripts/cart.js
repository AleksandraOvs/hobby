// document.addEventListener('click', function (e) {
//     const btn = e.target.closest('.qty-btn');
//     if (!btn) return;

//     e.preventDefault();

//     const container = btn.closest('.pro-qty');
//     if (!container) return;

//     const input = container.querySelector('input[name*="[qty]"]');
//     if (!input) return;

//     let value = parseFloat(input.value);
//     if (isNaN(value)) value = parseFloat(input.min) || 1;

//     const step = parseFloat(input.step) || 1;
//     const min = parseFloat(input.min) || 1;
//     const max = input.max ? parseFloat(input.max) : Infinity;

//     if (btn.classList.contains('inc')) value += step;
//     if (btn.classList.contains('dec')) value -= step;

//     value = Math.max(min, value);
//     value = Math.min(max, value);

//     input.value = value;

//     input.dispatchEvent(new Event('input', { bubbles: true }));
//     input.dispatchEvent(new Event('change', { bubbles: true }));
// });

// document.addEventListener('click', function (e) {
//     console.log('Клик вообще есть:', e.target);

//     const btn = e.target.closest('.qty-btn');
//     if (!btn) return;

//     console.log('Клик по кнопке qty-btn:', btn);

//     const container = btn.closest('.pro-qty');
//     console.log('Найден контейнер .pro-qty:', container);

//     if (!container) return;

//     const input = container.querySelector('input');
//     console.log('Найден input:', input);

//     if (!input) return;

//     console.log('Текущее значение input.value:', input.value);
//     console.log('type:', input.type, 'min:', input.min, 'max:', input.max, 'step:', input.step);

//     let value = parseFloat(input.value);
//     if (isNaN(value)) value = parseFloat(input.min) || 1;

//     if (btn.classList.contains('inc')) value += 1;
//     if (btn.classList.contains('dec')) value -= 1;

//     console.log('Новое значение, которое пытаемся установить:', value);

//     input.value = value;

//     input.dispatchEvent(new Event('input', { bubbles: true }));
//     input.dispatchEvent(new Event('change', { bubbles: true }));

//     console.log('Значение после установки:', input.value);
// });

document.addEventListener('DOMContentLoaded', () => {
    // Таймер для обновления корзины на странице cart
    let cartUpdateTimer = null;

    // Обработчик клика на кнопки + / -
    document.addEventListener('click', e => {
        const btn = e.target.closest('.qty-btn');
        if (!btn) return;

        e.preventDefault();

        const wrap = btn.closest('.pro-qty');
        if (!wrap) return;

        const input = wrap.querySelector('input');
        if (!input) return;

        // Берём текущее значение как число
        let value = parseFloat(input.value) || 0;
        const step = parseFloat(input.dataset.step) || 1;
        const min = parseFloat(input.dataset.min) || 1;
        const max = input.dataset.max ? parseFloat(input.dataset.max) : Infinity;

        // Увеличиваем или уменьшаем
        if (btn.classList.contains('inc')) value += step;
        if (btn.classList.contains('dec')) value -= step;

        // Ограничиваем диапазон
        value = Math.max(min, value);
        value = Math.min(max, value);

        // Устанавливаем именно property, чтобы визуально обновилось
        input.value = value;

        // Триггерим события для WooCommerce и других слушателей
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.dispatchEvent(new Event('change', { bubbles: true }));

        // Обновление корзины на странице cart
        if (document.body.classList.contains('woocommerce-cart')) {
            clearTimeout(cartUpdateTimer);
            cartUpdateTimer = setTimeout(() => {
                const updateBtn = document.querySelector('button[name="update_cart"]');
                if (updateBtn) {
                    updateBtn.disabled = false;
                    updateBtn.click();
                }
            }, 400);
        }
    });
});

document.addEventListener('change', e => {
    const cb = e.target.closest('.cart-item-checkbox');
    if (!cb) return;

    const formData = new FormData();
    formData.append('action', 'toggle_cart_item');
    formData.append('cart_item_key', cb.name.match(/\[(.*?)\]/)[1]);
    formData.append('selected', cb.checked ? '1' : '0');

    fetch(cart_ajax.ajax_url, {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
    }).then(() => {
        // пересчёт итогов
        fetch(cart_ajax.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        }).then(() => {

            // пересчёт корзины
            jQuery(document.body).trigger('wc_update_cart');
            jQuery(document.body).trigger('wc_fragment_refresh');

            // если ты на checkout
            jQuery(document.body).trigger('update_checkout');
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {

    const selectAll = document.getElementById('select-all');
    const countEl = document.getElementById('selected-count');
    const removeBtn = document.getElementById('remove-selected');

    function getCheckboxes() {
        return document.querySelectorAll('.cart-item-checkbox');
    }

    function updateCount() {
        const checked = document.querySelectorAll('.cart-item-checkbox:checked').length;
        countEl.textContent = checked;

        const all = getCheckboxes().length;
        if (selectAll) {
            selectAll.checked = all > 0 && checked === all;
        }
    }

    // выбрать все
    document.addEventListener('change', function (e) {
        if (e.target.id === 'select-all') {
            getCheckboxes().forEach(cb => cb.checked = e.target.checked);
            updateCount();
        }
    });

    // изменение одного
    document.addEventListener('change', function (e) {
        if (!e.target.classList.contains('cart-item-checkbox')) return;
        updateCount();
    });

    // удаление выбранных
    document.addEventListener('click', function (e) {
        if (e.target.closest('#remove-selected')) {

            const selected = document.querySelectorAll('.cart-item-checkbox:checked');
            if (!selected.length) return;

            let requests = [];

            selected.forEach(cb => {
                const row = cb.closest('.cart_item');
                const link = row?.querySelector('.remove');
                if (link) {
                    requests.push(fetch(link.href, {
                        credentials: 'same-origin'
                    }));
                }
            });

            Promise.all(requests).then(() => location.reload());
        }
    });

    updateCount();
});
