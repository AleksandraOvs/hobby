// document.addEventListener('DOMContentLoaded', () => {

//     const button = document.getElementById('load-more-posts');
//     console.log('Кнопка load-more-posts:', button);

//     if (!button) {
//         console.warn('Кнопка не найдена. Проверьте ID и наличие на странице.');
//         return;
//     }

//     console.log('themeAjax:', window.themeAjax);

//     if (!window.themeAjax) {
//         console.warn('themeAjax не определён. Проверьте wp_localize_script() и handle скрипта.');
//         return;
//     }
// });

console.log('button:', document.getElementById('load-more-posts'));

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

    //----------------------------подгрузка товаров-----------------------------------//

    const btn = document.getElementById('load-more-btn');
    const box = document.querySelector('.products-wrapper');

    if (!btn || !box) return;

    // 👉 если при загрузке страницы уже нет следующей страницы — скрываем кнопку
    if (!document.querySelector('.page-numbers .next')) {
        btn.remove();
    }

    btn.addEventListener('click', () => {
        const nextLink = document.querySelector('.page-numbers .next');

        if (!nextLink) {
            btn.remove();
            return;
        }

        btn.classList.add('is-loading');
        btn.disabled = true;

        fetch(nextLink.href)
            .then(res => res.text())
            .then(html => {
                const doc = new DOMParser().parseFromString(html, 'text/html');

                // новые товары
                const newProducts = doc.querySelectorAll('.products-wrapper > *');

                // 👉 если сервер ничего не вернул — кнопка больше не нужна
                if (!newProducts.length) {
                    btn.remove();
                    return;
                }

                newProducts.forEach(el => box.appendChild(el));

                // обновляем пагинацию
                const newPagination = doc.querySelector('.page-pagination-wrapper');
                const currentPagination = document.querySelector('.page-pagination-wrapper');

                if (newPagination && currentPagination) {
                    currentPagination.innerHTML = newPagination.innerHTML;
                }

                btn.classList.remove('is-loading');
                btn.disabled = false;
                btn.textContent = 'Показать ещё';

                // 👉 если дальше страниц нет — убираем кнопку
                if (!document.querySelector('.page-numbers .next')) {
                    btn.remove();
                }
            })
            .catch(() => {
                btn.classList.remove('is-loading');
                btn.disabled = false;
            });
    });

});
//---------------------подгрузка постов------------------------------//

document.addEventListener('DOMContentLoaded', () => {

    const grid = document.querySelector('.posts-grid');
    const btn = document.getElementById('load-more-posts');

    if (!btn || !grid || !window.themeAjax) return;

    // ✅ Переносим переменную currentPage в область, доступную обработчику клика
    let currentPage = parseInt(btn.dataset.page, 10) || 1;
    const maxPage = parseInt(btn.dataset.max, 10) || 1;

    btn.addEventListener('click', function () { // используем function() вместо стрелочной функции
        if (currentPage >= maxPage) {
            btn.remove();
            return;
        }

        btn.classList.add('is-loading');

        const formData = new FormData();
        formData.append('action', 'load_more_posts');
        formData.append('page', currentPage);

        fetch(window.themeAjax.url, {
            method: 'POST',
            body: formData
        })
            .then(res => res.text())
            .then(html => {

                if (!html.trim()) {
                    btn.remove();
                    return;
                }

                grid.insertAdjacentHTML('beforeend', html);

                currentPage++; // ✅ теперь currentPage точно доступен
                btn.dataset.page = currentPage;

                if (currentPage >= maxPage) {
                    btn.remove();
                }
            })
            .finally(() => {
                btn.classList.remove('is-loading');
            });
    });

});

