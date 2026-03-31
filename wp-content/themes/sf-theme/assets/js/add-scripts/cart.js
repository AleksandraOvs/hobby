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

    // получаем ключ cart item
    const key = cb.dataset.key || (cb.name?.match(/\[(.*?)\]/)?.[1]);
    if (!key) return; // безопасно выходим, если нет ключа

    const selected = cb.checked;

    // AJAX запрос на сервер
    fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            action: 'toggle_cart_item',
            cart_item_key: key,
            selected: selected ? 1 : 0
        })
    }).then(res => res.json())
        .then(res => {
            if (res.success) {
                const totalEl = document.querySelector('.order-total p');
                if (totalEl) totalEl.innerHTML = res.data.total;

                const subtotalEl = document.querySelector('.cart-subtotal p');
                if (subtotalEl) subtotalEl.innerHTML = res.data.subtotal;

                const countEl = document.querySelector('.cart-items-count-value');
                if (countEl) countEl.textContent = res.data.items_count;

                const weightEl = document.querySelector('.cart-weight-value');
                if (weightEl) weightEl.textContent = res.data.weight;

                if (res.data.mini_cart) {
                    document.querySelector('.widget_shopping_cart_content').innerHTML = res.data.mini_cart;
                    // const miniCart = document.querySelector('.widget_shopping_cart_content');
                    // if (miniCart) miniCart.innerHTML = res.data.mini_cart;
                }


                // ✅ отдельные триггеры для каждого события
                jQuery(document.body).trigger('wc_update_cart');
                //jQuery(document.body).trigger('wc_fragment_refresh');
                jQuery(document.body).trigger('update_checkout');
            }
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

        if (countEl) {  // <-- проверяем, существует ли элемент
            countEl.textContent = checked;
        }

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

//отмечать подпункты сдэк только если отмечен основой пункт СДЭК

document.addEventListener('DOMContentLoaded', function () {
    const deliveryRadios = document.querySelectorAll('input[name="custom_delivery_method"]');
    const sdekSuboptions = document.querySelector('.sdek-suboptions');

    if (!sdekSuboptions) return;

    // Функция для показа/скрытия СДЭК-подвариантов
    function toggleSdekOptions() {
        const selected = document.querySelector('input[name="custom_delivery_method"]:checked');
        if (selected && selected.value === 'СДЭК') {
            sdekSuboptions.style.display = 'flex';
        } else {
            // Скрываем и снимаем все галочки
            sdekSuboptions.style.display = 'none';
            sdekSuboptions.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        }
    }

    // Запуск при загрузке страницы
    toggleSdekOptions();

    // Запуск при каждом изменении выбора доставки
    deliveryRadios.forEach(radio => {
        radio.addEventListener('change', toggleSdekOptions);
    });
});