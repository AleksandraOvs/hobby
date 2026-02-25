document.addEventListener("DOMContentLoaded", function () {

    const buttons = document.querySelectorAll('a[href="#main-form"]');

    buttons.forEach(function (button) {
        button.setAttribute('data-fancybox', '');
        button.setAttribute('data-type', 'inline');
    });

    // === Fancybox (v4) + href="#main-form" ===
    if (typeof Fancybox !== "undefined") {
        Fancybox.bind("[data-fancybox]", { autoFocus: true });
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
});