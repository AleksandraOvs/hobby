// ui.js


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


document.addEventListener('DOMContentLoaded', () => {

    /* ===============================
       КАТАЛОЖНОЕ МЕНЮ
    =============================== */

    const toggleBtn = document.querySelector('.toggle-cat-menu-button');
    const menuContainer = document.getElementById('menu-catalog__container');
    const catMenu = document.getElementById('menu-catalog');

    if (toggleBtn && menuContainer && catMenu) {

        const isMobile = () => window.matchMedia('(max-width: 991px)').matches;

        const closeAllSubmenus = () => {
            catMenu.querySelectorAll('li.is-open')
                .forEach(li => li.classList.remove('is-open'));
        };

        toggleBtn.addEventListener('click', e => {
            e.preventDefault();
            const opened = menuContainer.classList.toggle('is-open');
            toggleBtn.classList.toggle('is-active', opened);
            if (!opened) closeAllSubmenus();
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
                if (!submenu || !isMobile()) return;
                e.preventDefault();
                item.classList.toggle('is-open');
            });
        });
    }

    /* ===============================
       ПЛАВНЫЙ СКРОЛЛ
    =============================== */

    const easeInOutQuad = t =>
        t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;

    const smoothScroll = (targetY, duration = 700) => {
        const el = document.scrollingElement;
        const start = el.scrollTop;
        const change = targetY - start;
        const startTime = performance.now();

        const animate = time => {
            const progress = Math.min((time - startTime) / duration, 1);
            el.scrollTop = start + change * easeInOutQuad(progress);
            if (progress < 1) requestAnimationFrame(animate);
        };

        requestAnimationFrame(animate);
    };

    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', e => {
            const id = link.getAttribute('href');
            if (id.length <= 1) return;
            const target = document.querySelector(id);
            if (!target) return;
            e.preventDefault();
            smoothScroll(target.getBoundingClientRect().top + window.scrollY - 160);
        });
    });

    /* ===============================
       КНОПКА "ВВЕРХ"
    =============================== */

    const upArrow = document.querySelector('.arrow-up');
    if (upArrow) {
        upArrow.addEventListener('click', e => {
            e.preventDefault();
            smoothScroll(0);
        });

        window.addEventListener('scroll', () => {
            upArrow.classList.toggle('show', window.scrollY > 300);
        });
    }



    /* ===============================
       WOOCOMMERCE MESSAGE AUTO-HIDE
    =============================== */

    document.addEventListener('click', e => {
        document.querySelectorAll('.woocommerce-message').forEach(msg => {
            if (!msg.contains(e.target)) {
                msg.classList.add('fade-out');
                setTimeout(() => msg.remove(), 700);
            }
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

    //форма входа/регистрации

    const tabs = document.querySelectorAll('.auth-tabs__title');
    const panels = document.querySelectorAll('.auth-tabs__panel');

    tabs.forEach(tab => {

        tab.addEventListener('click', function () {

            const target = this.dataset.tab;

            tabs.forEach(t => t.classList.remove('is-active'));
            panels.forEach(p => p.classList.remove('is-active'));

            this.classList.add('is-active');

            document
                .querySelector('[data-panel="' + target + '"]')
                .classList.add('is-active');

        });

    });

})

document.addEventListener('DOMContentLoaded', function () {

    const mobileMenu = document.querySelector('.mobile-menu__inner');
    if (!mobileMenu) return;

    mobileMenu.addEventListener('click', function (e) {

        const link = e.target.closest('.menu-item-has-children > a');
        if (!link) return;

        const parent = link.parentElement;
        const dropdown = parent.querySelector(':scope > .dropdown-menu');

        if (!dropdown) return;

        // если уже открыто → разрешаем переход
        if (dropdown.classList.contains('show')) {
            return;
        }

        // первый клик → открываем
        e.preventDefault();

        // 🔹 закрываем только соседние уровни (важно для вложенности)
        const siblings = parent.parentElement.querySelectorAll(':scope > .menu-item > .dropdown-menu.show');
        siblings.forEach(el => {
            if (el !== dropdown) el.classList.remove('is-open');
        });

        dropdown.classList.add('is-open');
    });

});