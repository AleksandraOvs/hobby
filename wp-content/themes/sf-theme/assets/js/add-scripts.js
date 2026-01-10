
// document.addEventListener('DOMContentLoaded', function () {
// 	const button = document.querySelector('.toggle-cat-menu-button');
// 	const menuContainer = document.getElementById('menu-catalog__container');

// 	if (!button || !menuContainer) return;

// 	const catMenu = document.getElementById('menu-catalog');
// 	if (!catMenu) return;

// 	const isMobile = () => window.matchMedia('(max-width: 991px)').matches;

// 	// Закрываем все подменю
// 	function closeAllSubmenus(parent = catMenu) {
// 		parent.querySelectorAll('li.is-open').forEach(li => li.classList.remove('is-open'));
// 	}

// 	// Клик по кнопке — открываем/закрываем контейнер, но подменю остаются закрыты
// 	button.addEventListener('click', function (e) {
// 		e.preventDefault();
// 		const isOpen = menuContainer.classList.toggle('is-open');
// 		button.classList.toggle('is-active', isOpen);

// 		// Подменю остаются закрытыми, закрываем только если меню закрыто
// 		if (!isOpen) {
// 			closeAllSubmenus();
// 		}
// 	});

// 	// Закрытие меню при клике вне его
// 	document.addEventListener('click', function (e) {
// 		if (!menuContainer.contains(e.target) && !button.contains(e.target)) {
// 			menuContainer.classList.remove('is-open');
// 			button.classList.remove('is-active');
// 			closeAllSubmenus();
// 		}
// 	});

// 	// Клик по пунктам меню
// 	catMenu.addEventListener('click', function (e) {
// 		const link = e.target.closest('a');
// 		if (!link) return;

// 		const item = link.closest('li');
// 		const submenu = item.querySelector(':scope > .dropdown-menu');
// 		if (!submenu) return;

// 		e.preventDefault();

// 		if (isMobile()) {
// 			// мобильная версия — просто переключаем подменю
// 			item.classList.toggle('is-open');
// 			return;
// 		}

// 		// десктоп — только первый уровень меню по клику
// 		if (item.parentElement.id === 'menu-catalog') {
// 			[...item.parentElement.children].forEach(li => {
// 				if (li !== item) {
// 					li.classList.remove('is-open');
// 					li.querySelectorAll('li.is-open').forEach(sub => sub.classList.remove('is-open'));
// 				}
// 			});

// 			item.classList.toggle('is-open');
// 		}
// 	});

// 	// При загрузке страницы меню закрыто, подменю закрыты
// 	menuContainer.classList.remove('is-open');
// 	button.classList.remove('is-active');
// 	closeAllSubmenus();
// });
document.addEventListener('click', function (e) {
    //console.log('menu script click');
    const toggleBtn = e.target.closest('.toggle-cat-menu-button');
    const menu = document.getElementById('menu-catalog__container');

    if (!menu) return;

    if (toggleBtn) {
        e.preventDefault();

        const isOpen = menu.classList.contains('is-open');
        menu.classList.toggle('is-open', !isOpen);
        toggleBtn.classList.toggle('active', !isOpen);
        return;
    }

    if (
        !e.target.closest('#menu-catalog__container') &&
        !e.target.closest('.toggle-cat-menu-button')
    ) {
        menu.classList.remove('is-open');
        document
            .querySelector('.toggle-cat-menu-button')
            ?.classList.remove('active');
    }
});

document.addEventListener("DOMContentLoaded", function () {

    // === Универсальный плавный скролл ===
    function easeInOutQuad(t) { return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t; }

    function smoothScrollToElement(selector, duration = 700) {
        const target = document.querySelector(selector);
        if (!target) return;
        document.documentElement.style.scrollBehavior = "auto";
        const element = document.scrollingElement || document.documentElement;
        const start = element.scrollTop;
        const targetTop = target.getBoundingClientRect().top + start - 160;
        const change = targetTop - start;
        const startTime = performance.now();

        function animate(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            element.scrollTop = start + change * easeInOutQuad(progress);
            if (elapsed < duration) requestAnimationFrame(animate);
            else document.documentElement.style.scrollBehavior = "";
        }
        requestAnimationFrame(animate);
    }

    function smoothScrollToTop(duration = 700) {
        const element = document.scrollingElement || document.documentElement;
        const start = element.scrollTop;
        const change = -start;
        const startTime = performance.now();

        function animate(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            element.scrollTop = start + change * easeInOutQuad(progress);
            if (elapsed < duration) requestAnimationFrame(animate);
        }
        requestAnimationFrame(animate);
    }

    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener("click", e => {
            const targetSelector = link.getAttribute("href");
            if (!targetSelector || targetSelector.length <= 1) return;

            // Если это форма для Fancybox
            if (targetSelector === "#main-form") {
                e.preventDefault();
                const target = document.querySelector(targetSelector);
                if (target) {
                    Fancybox.show([{ src: target, type: "inline" }]);
                }
                return; // не запускать плавный скролл
            }

            // Для остальных якорей — плавный скролл
            e.preventDefault();
            smoothScrollToElement(targetSelector, 800);
        });
    });


    // === Кнопка "вверх" ===
    const upArrow = document.querySelector(".arrow-up");
    if (upArrow) {
        upArrow.addEventListener("click", e => { e.preventDefault(); smoothScrollToTop(800); });
        window.addEventListener("scroll", () => {
            upArrow.classList.toggle("show", window.scrollY > 300);
        });
    }

    function isMobile() {
        return window.innerWidth < 1024;
    }

    document.querySelectorAll('.menu-item.menu-item-has-children > a')
        .forEach(link => {

            link.addEventListener('click', function (e) {

                if (!isMobile()) return;

                const parentItem = this.parentElement;
                const submenu = parentItem.querySelector(':scope > ul');

                if (!submenu) return;

                // если подменю еще не открыто — открываем и запрещаем переход
                if (!submenu.classList.contains('is-open')) {
                    e.preventDefault();
                    submenu.classList.add('is-open');
                }
                // если уже открыто — ничего не делаем, переход по ссылке разрешен
            });
        });

});

document.addEventListener('DOMContentLoaded', () => {

    const container = document.querySelector('.mobile-menu__container');
    if (!container) return;

    const mobMenu = container.querySelector('.mobile-menu');
    const inner = container.querySelector('.mobile-menu__inner');

    const anchorLinks = mobMenu.querySelectorAll('a[href^="#"]');
    const items = inner.querySelectorAll('.mm-item');
    const closeBtn = inner.querySelector('.mobile-menu__close');

    function closeAll() {
        container.classList.remove('is-open');
        inner.classList.remove('is-open');

        items.forEach(item => {
            item.classList.remove('is-open');
        });
    }

    // клики по якорям в меню
    anchorLinks.forEach(link => {
        link.addEventListener('click', e => {

            const targetId = link.getAttribute('href').slice(1);
            if (!targetId) return;

            const targetItem = inner.querySelector(
                '#' + CSS.escape(targetId)
            );
            if (!targetItem) return;

            e.preventDefault();

            closeAll();

            container.classList.add('is-open');
            inner.classList.add('is-open');
            targetItem.classList.add('is-open');
        });
    });

    // кнопка закрытия
    if (closeBtn) {
        closeBtn.addEventListener('click', e => {
            e.preventDefault();
            closeAll();
        });
    }

    // клик по overlay (.mobile-menu__container.is-open)
    container.addEventListener('click', e => {
        if (
            container.classList.contains('is-open') &&
            e.target === container
        ) {
            closeAll();
        }
    });

    // === Мобильное меню ===
    const body = document.body;
    const menu = document.querySelector(".mobile-nav");
    const burger = document.querySelector(".menu-toggle");
    document.addEventListener("click", function (e) {
        if (burger && e.target.closest(".menu-toggle")) {
            e.stopPropagation();
            burger.classList.toggle("active");
            if (menu) menu.classList.toggle("active");
            body.classList.toggle("_fixed");
            return;
        }
        if (menu && e.target.closest(".mobile-menu .main-navigation a")) {
            if (burger) burger.classList.remove("active");
            menu.classList.remove("active");
            body.classList.remove("_fixed");
            return;
        }
        if (menu && !e.target.closest(".mobile-menu") && burger) {
            burger.classList.remove("active");
            menu.classList.remove("active");
            body.classList.remove("_fixed");
        }
    });

    /**открытие/скрытие описания товара на странице товара */

    document.querySelectorAll('.single-product-details__description').forEach(block => {

        const p = block.querySelector('p');
        const btn = block.querySelector('.single-product-details__more');

        if (!p || !btn) return;

        if (p.scrollHeight <= p.clientHeight) {
            btn.style.display = 'none';
            return;
        }

        btn.addEventListener('click', () => {
            block.classList.toggle('is-open');
            btn.textContent = block.classList.contains('is-open')
                ? 'Свернуть описание'
                : 'Полное описание';
        });

    });

    document
        .querySelectorAll('.single-product-details .product-specs')
        .forEach(specs => {

            const rows = specs.querySelectorAll('.product-specs__row');
            if (rows.length <= 6) return;

            // скрываем всё после 6
            rows.forEach((row, index) => {
                if (index >= 6) {
                    row.style.display = 'none';
                }
            });

            // создаём кнопку
            const toggle = document.createElement('button');
            toggle.type = 'button';
            toggle.className = 'product-specs__toggle';
            toggle.textContent = 'Все характеристики';

            specs.appendChild(toggle);

            let opened = false;

            toggle.addEventListener('click', () => {
                opened = !opened;

                rows.forEach((row, index) => {
                    if (index >= 6) {
                        row.style.display = opened ? 'grid' : 'none';
                    }
                });

                toggle.textContent = opened
                    ? 'Скрыть характеристики'
                    : 'Все характеристики';
            });
        });

    document
        .querySelectorAll('.sidebar-area-wrapper .single-sidebar-wrap .sidebar-body .sidebar-list')
        .forEach(list => {

            const items = list.querySelectorAll('li');

            // если элементов мало — ничего не делаем
            if (items.length <= 4) return;

            // скрываем всё после 6-го
            items.forEach((item, index) => {
                if (index >= 4) {
                    item.style.display = 'none';
                }
            });

            // создаём кнопку
            const toggle = document.createElement('button');
            toggle.type = 'button';
            toggle.className = 'sidebar-list__toggle';
            toggle.textContent = 'Показать ещё';

            list.appendChild(toggle);

            let opened = false;

            toggle.addEventListener('click', () => {
                opened = !opened;

                items.forEach((item, index) => {
                    if (index >= 4) {
                        item.style.display = opened ? 'list-item' : 'none';
                    }
                });

                toggle.textContent = opened
                    ? 'Скрыть'
                    : 'Показать ещё';
            });
        });

    //пересчет цены в зависимости от количества на странице товара

    const addToCartBlock = document.querySelector('.single-product-add-to-cart form.cart');
    if (!addToCartBlock) return;

    const qtyInput = addToCartBlock.querySelector('input.qty');
    const totalEl = addToCartBlock.querySelector('.price-total');
    const priceWrap = document.querySelector('.prices-group'); // берем data-price оттуда

    if (!qtyInput || !totalEl || !priceWrap) return;

    const formatPrice = (price) =>
        new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB' }).format(price);

    const getBasePrice = () => parseFloat(priceWrap.dataset.price) || 0;

    let lastQty = qtyInput.value;

    const updateTotal = () => {
        const qty = parseInt(qtyInput.value, 10) || 1;
        totalEl.textContent = formatPrice(getBasePrice() * qty);
        lastQty = qtyInput.value;
    };

    // первая отрисовка
    updateTotal();

    // таймер для любых изменений (клики +/-, ручной ввод)
    setInterval(() => {
        if (qtyInput.value !== lastQty) {
            updateTotal();
        }
    }, 100);

    // пересчет цены в зависимости от количества с учетом bulk-скидок
    // document.addEventListener('DOMContentLoaded', () => {
    //     const addToCartBlock = document.querySelector('.single-product-add-to-cart form.cart');
    //     if (!addToCartBlock) return;

    //     const qtyInput = addToCartBlock.querySelector('input.qty');
    //     const totalEl = addToCartBlock.querySelector('.price-total');
    //     const priceWrap = document.querySelector('.prices-group');
    //     const priceSingleEl = priceWrap?.querySelector('.price-single');
    //     const bulkDataEl = document.getElementById('bulk-discount-data');

    //     if (!qtyInput || !priceWrap || !priceSingleEl || !bulkDataEl) return;

    //     let discounts = [];
    //     try {
    //         const bulkData = JSON.parse(bulkDataEl.textContent);
    //         discounts = bulkData.discounts || [];
    //     } catch (e) {
    //         console.warn('Ошибка при чтении bulk JSON', e);
    //     }

    //     const basePrice = parseFloat(priceWrap.dataset.price) || 0;

    //     const formatPrice = (price) =>
    //         new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB' }).format(price);

    //     const getUnitPriceByQty = (qty) => {
    //         let applicableDiscount = 0;

    //         // находим максимальный min_qty <= qty
    //         discounts.forEach(row => {
    //             if (qty >= row.min_qty) {
    //                 applicableDiscount = parseFloat(row.discount) || 0;
    //             }
    //         });

    //         // цена за 1 шт с учетом скидки
    //         return basePrice * (1 - applicableDiscount / 100);
    //     };

    //     const updatePrices = () => {
    //         const qty = parseInt(qtyInput.value, 10) || 1;
    //         const unitPrice = getUnitPriceByQty(qty);
    //         const total = unitPrice * qty;

    //         // цена за 1 шт
    //         priceSingleEl.textContent = formatPrice(unitPrice);

    //         // итоговая цена
    //         if (totalEl) {
    //             totalEl.textContent = formatPrice(total);
    //         }
    //     };

    //     // начальная отрисовка
    //     updatePrices();

    //     // отслеживаем ручной ввод
    //     qtyInput.addEventListener('input', updatePrices);

    //     // отслеживаем клики на +/- кнопки
    //     addToCartBlock.querySelectorAll('.inc, .dec').forEach(btn => {
    //         btn.addEventListener('click', () => setTimeout(updatePrices, 50));
    //     });
    // });

});

// document.addEventListener('DOMContentLoaded', () => {

//     document.body.addEventListener('click', (e) => {
//         const btn = e.target.closest('.single_add_to_cart_button');
//         if (!btn) return;

//         e.preventDefault(); // отменяем стандартную отправку формы

//         const form = btn.closest('form.cart');
//         if (!form) return;

//         const formData = new FormData(form);
//         formData.append('action', 'ajax_add_to_cart');

//         // product_id берем из value кнопки
//         const product_id = btn.value || form.querySelector('input[name="product_id"]')?.value;
//         if (!product_id) {
//             console.error('product_id не найден');
//             return;
//         }
//         formData.append('add-to-cart', product_id);

//         // кнопка в состоянии загрузки
//         btn.disabled = true;
//         btn.classList.add('is-loading');
//         const originalText = btn.textContent;
//         btn.textContent = 'Добавляем…';

//         fetch(window.themeAjax.url, {
//             method: 'POST',
//             body: formData
//         })
//             .then(res => res.json())
//             .then(data => {
//                 if (data.success) {

//                     // удаляем старые уведомления WooCommerce
//                     document.querySelectorAll('.woocommerce-message').forEach(el => el.remove());

//                     // создаем новое уведомление
//                     const message = document.createElement('div');
//                     message.className = 'woocommerce-message';
//                     message.innerHTML = 'Товар добавлен в корзину! <button class="woocommerce-message-close" aria-label="Закрыть">×</button>';

//                     // вставляем уведомление
//                     const noticesWrapper = document.querySelector('.woocommerce-notices-wrapper');
//                     if (noticesWrapper) {
//                         noticesWrapper.appendChild(message);
//                     } else {
//                         form.parentNode.insertBefore(message, form);
//                     }

//                     // обработка кнопки закрытия
//                     message.querySelector('.woocommerce-message-close').addEventListener('click', () => {
//                         message.remove();
//                     });

//                     // автоматическое скрытие через 10 секунд (5000)
//                     setTimeout(() => {
//                         if (message.parentNode) message.remove();
//                     }, 5000);

//                     // обновляем счетчик корзины, если есть
//                     const cartCount = document.querySelector('.cart-count');
//                     if (cartCount && data.cart_count !== undefined) {
//                         cartCount.textContent = data.cart_count;
//                     }

//                 } else {
//                     console.error('Ошибка добавления товара');
//                 }
//             })
//             .finally(() => {
//                 btn.disabled = false;
//                 btn.classList.remove('is-loading');
//                 btn.textContent = originalText;
//             });

//     });

// });

jQuery(function ($) {

    let updateTimer;

    $(document).on('change', '.qty', function () {

        clearTimeout(updateTimer);

        updateTimer = setTimeout(function () {

            const $form = $('form.woocommerce-cart-form');

            if (!$form.length) return;

            const formData = $form.serialize();

            $.ajax({
                url: wc_cart_params.wc_ajax_url.replace('%%endpoint%%', 'update_cart'),
                type: 'POST',
                data: formData,
                success: function () {

                    // Обновляем фрагменты (итоги, мини-корзина и т.п.)
                    $(document.body).trigger('wc_fragment_refresh');

                    // Перерисовываем корзину
                    $(document.body).trigger('updated_cart_totals');
                }
            });

        }, 300);
    });
});


(function ($) {

    function updateCartAjax() {

        var $form = $('.woocommerce-cart-form');

        // Добавляем флаг "обновить корзину"
        if (!$form.find('input[name="update_cart"]').length) {
            $form.append('<input type="hidden" name="update_cart" value="1">');
        }

        $.ajax({
            type: 'POST',
            url: wc_cart_params.wc_ajax_url.replace('%%endpoint%%', 'update_cart'),
            data: $form.serialize(),
            success: function () {
                // WooCommerce сам перерисует фрагменты (итоги, мини-корзину и т.д.)
                $(document.body).trigger('wc_fragment_refresh');
            }
        });
    }

    // слушаем чекбоксы
    $(document).on('change', '.product-select input[type="checkbox"]', function () {
        updateCartAjax();
    });

})(jQuery);

document.addEventListener('DOMContentLoaded', () => {

    document.body.addEventListener('click', (e) => {
        const btn = e.target.closest('.single_add_to_cart_button');
        if (!btn) return;

        e.preventDefault(); // отменяем стандартную отправку формы

        const form = btn.closest('form.cart');
        if (!form) return;

        const formData = new FormData(form);

        // product_id
        const product_id =
            btn.value ||
            form.querySelector('input[name="product_id"]')?.value;

        if (!product_id) {
            console.error('product_id не найден');
            return;
        }

        // важно — именно add-to-cart (WooCommerce ждёт это поле)
        formData.append('add-to-cart', product_id);

        // кнопка в состоянии загрузки
        btn.disabled = true;
        btn.classList.add('is-loading');
        const originalText = btn.textContent;
        btn.textContent = 'Добавляем…';

        // используем нативный endpoint WooCommerce
        const url = wc_add_to_cart_params.wc_ajax_url.replace(
            '%%endpoint%%',
            'add_to_cart'
        );

        fetch(url, {
            method: 'POST',
            body: formData
        })
            .then(() => {

                // WooCommerce сам обновит мини-корзину
                document.body.dispatchEvent(new Event('wc_fragment_refresh'));

                // удаляем старые сообщения
                document.querySelectorAll('.woocommerce-message').forEach(el => el.remove());

                const message = document.createElement('div');
                message.className = 'woocommerce-message';
                message.innerHTML =
                    'Товар добавлен в корзину! <button class="woocommerce-message-close" aria-label="Закрыть">×</button>';

                const noticesWrapper = document.querySelector('.woocommerce-notices-wrapper');
                if (noticesWrapper) {
                    noticesWrapper.appendChild(message);
                } else {
                    form.parentNode.insertBefore(message, form);
                }

                message
                    .querySelector('.woocommerce-message-close')
                    .addEventListener('click', () => message.remove());

                setTimeout(() => {
                    if (message.parentNode) message.remove();
                }, 5000);
            })
            .catch(err => {
                console.error('Ошибка добавления товара', err);
            })
            .finally(() => {
                btn.disabled = false;
                btn.classList.remove('is-loading');
                btn.textContent = originalText;
            });

    });

});

(function ($) {

    function updateCartAjax() {
        var $form = $('.woocommerce-cart-form');

        if (!$form.find('input[name="update_cart"]').length) {
            $form.append('<input type="hidden" name="update_cart" value="1">');
        }

        $.ajax({
            type: 'POST',
            url: wc_cart_params.wc_ajax_url.replace('%%endpoint%%', 'update_cart'),
            data: $form.serialize(),
            success: function () {
                $(document.body).trigger('wc_fragment_refresh');
            }
        });
    }

    $(document).on('change', '.product-select input[type="checkbox"]', function () {
        updateCartAjax();
    });

})(jQuery);

jQuery(function ($) {

    let updateTimer;

    $(document).on('change', '.qty', function () {

        clearTimeout(updateTimer);

        updateTimer = setTimeout(function () {

            const $form = $('form.woocommerce-cart-form');

            if (!$form.length) return;

            const formData = $form.serialize();

            $.ajax({
                url: wc_cart_params.wc_ajax_url.replace('%%endpoint%%', 'update_cart'),
                type: 'POST',
                data: formData,
                success: function () {

                    // Обновляем фрагменты (итоги, мини-корзина и т.п.)
                    $(document.body).trigger('wc_fragment_refresh');

                    // Перерисовываем корзину
                    $(document.body).trigger('updated_cart_totals');
                }
            });

        }, 300);
    });
});