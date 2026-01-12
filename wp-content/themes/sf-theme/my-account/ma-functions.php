<?php
// 1️⃣ Добавляем вкладку в меню ЛК
add_filter('woocommerce_account_menu_items', function ($items) {
    $items['favorites'] = 'Избранное'; // добавляем вкладку
    return $items;
}, 20);

// 2️⃣ Регистрируем endpoint для вкладки
add_action('init', function () {
    add_rewrite_endpoint('favorites', EP_PAGES);
});

// 3️⃣ Выводим контент вкладки напрямую
add_action('woocommerce_account_favorites_endpoint', function () {

    echo '<h3>Избранное</h3>';
    echo do_shortcode('[custom_wishlist]'); // сюда вставьте ваш шорткод

});
