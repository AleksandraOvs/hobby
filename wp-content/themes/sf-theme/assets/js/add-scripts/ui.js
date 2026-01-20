// ui.js
document.addEventListener('click', function (e) {
    console.log('menu script click');
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

    /* ===============================
      КНОПКА ОТКРЫТИЯ ФИЛЬТРА НА <992PX
   =============================== */

    const button = document.querySelector('button.toggle-filter');
    const sidebar = document.querySelector('.sidebar-area-wrapper._filters');

    if (!button || !sidebar) return;

    button.addEventListener('click', () => {
        if (window.innerWidth <= 992) {
            sidebar.classList.toggle('show');
        }
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

})