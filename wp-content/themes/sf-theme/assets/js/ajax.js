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

    const btn = document.getElementById('load-more-btn');
    const box = document.querySelector('.products-wrapper');

    if (!btn || !box) return;

    btn.addEventListener('click', () => {
        const nextLink = document.querySelector('.page-numbers .next');

        if (!nextLink) {
            btn.remove();
            return;
        }

        // btn.disabled = true;
        // btn.textContent = 'Загружаем…';

        btn.classList.add('is-loading');

        fetch(nextLink.href)
            .then(res => res.text())
            .then(html => {
                const doc = new DOMParser().parseFromString(html, 'text/html');

                // новые товары
                const newProducts = doc.querySelectorAll('.products-wrapper > *');
                newProducts.forEach(el => box.appendChild(el));

                // обновляем пагинацию
                const newPagination = doc.querySelector('.page-pagination-wrapper');
                const currentPagination = document.querySelector('.page-pagination-wrapper');

                if (newPagination && currentPagination) {
                    currentPagination.innerHTML = newPagination.innerHTML;
                }

                btn.disabled = false;
                btn.textContent = 'Показать ещё';

                // если дальше страниц нет — убираем кнопку
                if (!document.querySelector('.page-numbers .next')) {
                    btn.remove();
                }
            });
    });

});