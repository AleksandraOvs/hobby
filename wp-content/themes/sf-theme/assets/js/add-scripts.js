document.addEventListener('DOMContentLoaded', () => {

    console.log('SCRIPT LOADED', Date.now());

    // ====== Меню каталога ======
    const toggleBtn = document.querySelector('.toggle-cat-menu-button');
    const menuContainer = document.getElementById('menu-catalog__container');
    const catMenu = document.getElementById('menu-catalog');

    if (toggleBtn && menuContainer && catMenu) {
        const isMobile = () => window.matchMedia('(max-width: 991px)').matches;

        function closeAllSubmenus(parent = catMenu) {
            parent.querySelectorAll('li.is-open').forEach(li => li.classList.remove('is-open'));
        }

        toggleBtn.addEventListener('click', e => {
            e.preventDefault();
            const isOpen = menuContainer.classList.toggle('is-open');
            toggleBtn.classList.toggle('is-active', isOpen);
            if (!isOpen) closeAllSubmenus();
        });

        document.addEventListener('click', e => {
            if (!menuContainer.contains(e.target) && !toggleBtn.contains(e.target)) {
                menuContainer.classList.remove('is-open');
                toggleBtn.classList.remove('is-active');
                closeAllSubmenus();
            }
        });

        catMenu.querySelectorAll('li > a').forEach(link => {
            link.addEventListener('click', e => {
                const item = link.closest('li');
                const submenu = item.querySelector(':scope > .dropdown-menu');
                if (!submenu) return;
                if (isMobile()) {
                    e.preventDefault();
                    item.classList.toggle('is-open');
                }
            });
        });
    }

    // ====== Плавный скролл ======
    function easeInOutQuad(t) { return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t; }

    function smoothScrollToElement(selector, duration = 700) {
        const target = document.querySelector(selector);
        if (!target) return;
        document.documentElement.style.scrollBehavior = "auto";
        const el = document.scrollingElement || document.documentElement;
        const start = el.scrollTop;
        const targetTop = target.getBoundingClientRect().top + start - 160;
        const change = targetTop - start;
        const startTime = performance.now();

        function animate(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            el.scrollTop = start + change * easeInOutQuad(progress);
            if (elapsed < duration) requestAnimationFrame(animate);
            else document.documentElement.style.scrollBehavior = "";
        }
        requestAnimationFrame(animate);
    }

    function smoothScrollToTop(duration = 700) {
        const el = document.scrollingElement || document.documentElement;
        const start = el.scrollTop;
        const change = -start;
        const startTime = performance.now();

        function animate(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            el.scrollTop = start + change * easeInOutQuad(progress);
            if (elapsed < duration) requestAnimationFrame(animate);
        }
        requestAnimationFrame(animate);
    }

    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', e => {
            const targetSelector = link.getAttribute('href');
            if (!targetSelector || targetSelector.length <= 1) return;
            e.preventDefault();
            smoothScrollToElement(targetSelector, 800);
        });
    });

    // ====== Кнопка "Вверх" ======
    const upArrow = document.querySelector('.arrow-up');
    if (upArrow) {
        upArrow.addEventListener('click', e => { e.preventDefault(); smoothScrollToTop(800); });
        window.addEventListener('scroll', () => upArrow.classList.toggle('show', window.scrollY > 300));
    }

    // ====== Мобильное меню ======
    const body = document.body;
    const mobileMenu = document.querySelector(".mobile-nav");
    const burger = document.querySelector(".menu-toggle");

    document.addEventListener("click", e => {
        if (burger && e.target.closest(".menu-toggle")) {
            e.stopPropagation();
            burger.classList.toggle("active");
            mobileMenu?.classList.toggle("active");
            body.classList.toggle("_fixed");
            return;
        }
        if (mobileMenu && e.target.closest(".mobile-menu .main-navigation a")) {
            burger?.classList.remove("active");
            mobileMenu.classList.remove("active");
            body.classList.remove("_fixed");
            return;
        }
        if (mobileMenu && !e.target.closest(".mobile-menu") && burger) {
            burger.classList.remove("active");
            mobileMenu.classList.remove("active");
            body.classList.remove("_fixed");
        }
    });
    // ====== Плавное скрытие сообщения  ======
    jQuery(function ($) {

        // Функция плавного скрытия
        function hideMessage($msg) {
            $msg.fadeOut(700, function () {
                $(this).remove();
            });
        }

        // Клик по кресту — тоже плавно скрываем
        $(document).on('click', '.woocommerce-message-close', function (e) {
            e.preventDefault();
            hideMessage($(this).closest('.woocommerce-message'));
        });

        // Клик в любом месте страницы
        $(document).on('click', function (e) {
            $('.woocommerce-message').each(function () {
                const $msg = $(this);
                if (!$msg.is(e.target) && $msg.has(e.target).length === 0) {
                    hideMessage($msg);
                }
            });
        });

    });

    // ====== Раскрытие описания и характеристик товара ======
    document.querySelectorAll('.single-product-details__description').forEach(block => {
        const p = block.querySelector('p');
        const btn = block.querySelector('.single-product-details__more');
        if (!p || !btn) return;
        if (p.scrollHeight <= p.clientHeight) btn.style.display = 'none';
        btn.addEventListener('click', () => {
            block.classList.toggle('is-open');
            btn.textContent = block.classList.contains('is-open') ? 'Свернуть описание' : 'Полное описание';
        });
    });

    document.querySelectorAll('.single-product-details .product-specs').forEach(specs => {
        const rows = specs.querySelectorAll('.product-specs__row');
        if (rows.length <= 6) return;
        rows.forEach((row, i) => { if (i >= 6) row.style.display = 'none'; });
        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'product-specs__toggle';
        toggle.textContent = 'Все характеристики';
        specs.appendChild(toggle);
        let opened = false;
        toggle.addEventListener('click', () => {
            opened = !opened;
            rows.forEach((row, i) => { if (i >= 6) row.style.display = opened ? 'grid' : 'none'; });
            toggle.textContent = opened ? 'Скрыть характеристики' : 'Все характеристики';
        });
    });

});

// =====================================
// SINGLE PRODUCT — AJAX PRICE UPDATE
// =====================================
document.addEventListener('DOMContentLoaded', () => {

    const addToCartBlock = document.querySelector('.single-product-add-to-cart form.cart');
    if (!addToCartBlock) return;

    const qtyInput = addToCartBlock.querySelector('input.qty');
    const totalEl = addToCartBlock.querySelector('.price-total');
    const priceSingle = addToCartBlock.querySelector('.price-single');

    if (!qtyInput || !totalEl || !priceSingle) return;

    const formatPrice = (price) =>
        new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB' }).format(price);

    const getBasePrice = () => parseFloat(priceSingle.dataset.basePrice) || 0;

    const updateTotal = () => {
        const qty = parseInt(qtyInput.value, 10) || 1;
        const basePrice = getBasePrice();
        const total = basePrice * qty;
        totalEl.textContent = formatPrice(total);
        console.log('PRICE UPDATE -> qty:', qty, 'total:', totalEl.textContent);
    };

    // кнопки + / - и ручной ввод
    const triggerUpdate = () => setTimeout(updateTotal, 0);

    qtyInput.addEventListener('input', triggerUpdate);
    qtyInput.addEventListener('change', triggerUpdate);

    addToCartBlock.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', triggerUpdate);
    });

    // первая отрисовка
    updateTotal();
});

// =====================================
// VARIATION PRODUCT — AJAX PRICE UPDATE
// =====================================

jQuery(function ($) {

    // проверяем, есть ли форма вариативного товара
    const $variationForm = $('form.variations_form');
    if (!$variationForm.length) return; // если нет вариаций — выходим

    function formatPrice(price) {
        return new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB' }).format(price);
    }

    // ====== Пересчет total при изменении qty ======
    function updateVariationTotal($container) {
        const $qty = $container.find('input.qty');
        const $total = $container.find('.price-total');
        const basePrice = parseFloat($container.data('price')) || 0;
        const qty = parseInt($qty.val(), 10) || 1;

        $total.text(formatPrice(basePrice * qty));
    }

    // ====== Для клика по +/- в qty
    $(document).on('click', '.quantity .qty-btn', function () {
        const $container = $(this).closest('.cart__price-update');
        if (!$container.length) return;
        setTimeout(() => updateVariationTotal($container), 0); // ждём обновления input
    });

    // ====== Для ручного ввода qty
    $(document).on('input change', '.cart__price-update input.qty', function () {
        const $container = $(this).closest('.cart__price-update');
        if (!$container.length) return;
        updateVariationTotal($container);
    });

    // ====== При выборе вариации
    $variationForm.on('found_variation', function (event, variation) {
        const $container = $(this).find('.cart__price-update');
        if (!$container.length) return;

        // обновляем базовую цену для пересчета
        $container.data('price', parseFloat(variation.display_price || 0));

        // пересчитываем total по текущему qty
        updateVariationTotal($container);
    });

});

// ================================
// CART QTY + AJAX UPDATE
// ================================
jQuery(function ($) {

    let cartTimer = null;

    function updateCart() {
        const $form = $('form.woocommerce-cart-form');
        if (!$form.length) return;

        $.ajax({
            type: 'POST',
            url: wc_cart_params.wc_ajax_url.replace('%%endpoint%%', 'update_cart'),
            data: $form.serialize(),
            success: function () {
                $(document.body).trigger('wc_fragment_refresh');
            }
        });
    }

    // 🔑 реагируем ТОЛЬКО на change
    $(document).on('change', '.woocommerce-cart-form input.qty', function () {
        clearTimeout(cartTimer);
        cartTimer = setTimeout(updateCart, 150);
    });

});

// jQuery(function ($) {
//     $(document).on('change', '.qty', function () {
//         $('body').trigger('update_cart'); // Это вызовет обновление корзины
//     });
// });

jQuery(function ($) {
    $(document).on('change input', '.qty', function () {
        $('button[name="update_cart"]').prop('disabled', false);
        $('body').trigger('update_cart'); // WooCommerce обновит корзину через AJAX
    });
});