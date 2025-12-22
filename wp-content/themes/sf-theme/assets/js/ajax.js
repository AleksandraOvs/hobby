document.addEventListener('DOMContentLoaded', () => {

    document.addEventListener('click', (e) => {

        const btn = e.target.closest('.load-more-subcats');
        if (!btn) return;

        const container = btn.closest('.categories-list__item');
        const hiddenItems = container.querySelectorAll('.subcategory-item.is-hidden');
        const step = parseInt(btn.dataset.step, 10);

        // показываем следующие 8
        Array.from(hiddenItems)
            .slice(0, step)
            .forEach(item => item.classList.remove('is-hidden'));

        // если скрытых больше нет — убираем кнопку
        if (container.querySelectorAll('.subcategory-item.is-hidden').length === 0) {
            btn.remove();
        }
    });

});