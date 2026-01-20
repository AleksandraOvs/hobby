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
