
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

});