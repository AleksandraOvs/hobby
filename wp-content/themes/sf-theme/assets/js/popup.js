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

    // Событие CF7 после успешной отправки
    document.addEventListener('wpcf7mailsent', function (event) {

        // Найдём форму внутри блока #main-form
        var mainForm = document.querySelector('#main-form');

        // Проверим, что форма именно внутри этого блока
        if (mainForm && mainForm.contains(event.target)) {

            // Скрываем саму форму
            event.target.style.display = 'none';

            // Добавляем сообщение об успешной отправке
            var successMessage = document.createElement('div');
            successMessage.className = 'main-form-success';
            successMessage.innerHTML = '<h3>Спасибо, форма отправлена.</h3> <p>Мы свяжемся с вами в ближайшее время.</p>';

            mainForm.appendChild(successMessage);
        }

    }, false);
});