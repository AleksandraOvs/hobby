document.addEventListener("DOMContentLoaded", function () {

    // === Список popup-якорей для Fancybox ===
    const fancyboxTargets = ['#main-form', '#popup-map'];

    fancyboxTargets.forEach(function (target) {
        const buttons = document.querySelectorAll(`a[href="${target}"]`);
        buttons.forEach(function (button) {
            button.setAttribute('data-fancybox', '');
            button.setAttribute('data-type', 'inline');
        });
    });

    // === Fancybox (v4) ===
    if (typeof Fancybox !== "undefined") {
        Fancybox.bind("[data-fancybox]", { autoFocus: true });
    }

    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener("click", e => {
            const targetSelector = link.getAttribute("href");
            if (!targetSelector || targetSelector.length <= 1) return;

            // Если это popup для Fancybox
            if (fancyboxTargets.includes(targetSelector)) {
                e.preventDefault();
                const target = document.querySelector(targetSelector);
                if (target) {
                    Fancybox.show([{ src: target, type: "inline" }]);
                }
                return;
            }

            // Остальные якоря — плавный скролл
            e.preventDefault();
            smoothScrollToElement(targetSelector, 800);
        });
    });
});