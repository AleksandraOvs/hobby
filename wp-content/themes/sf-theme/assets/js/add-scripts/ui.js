// ui.js
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
